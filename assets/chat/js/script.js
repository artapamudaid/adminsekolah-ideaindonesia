
const API_BASE_URL = 'https://halo.indoweb.id/api_chat'; 

let unreadCount = 0;
let isWidgetOpen = false;
let currentFile = null;

$(document).ready(function() {
    
    // 1. EMOJI
    const picker = new EmojiButton({ position: 'top-start', zIndex: 99999, theme: 'light', autoHide: false });
    const emojiBtn = document.querySelector('#btn-emoji');
    const msgInput = document.querySelector('#widget-input-msg');

    picker.on('emoji', emoji => { msgInput.value += emoji; msgInput.focus(); });
    emojiBtn.addEventListener('click', () => { picker.togglePicker(emojiBtn); });

    // 2. PREVIEW FILE
    $('#widget-file').on('change', function() {
        if (this.files && this.files.length > 0) {
            currentFile = this.files[0];
            showFilePreview(currentFile);
        }
    });

    function showFilePreview(file) {
        let reader = new FileReader();
        if (file.type.startsWith('image/')) {
            reader.onload = function(e) { $('#preview-content').html(`<img src="${e.target.result}">`); }
            reader.readAsDataURL(file);
        } else {
            let size = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            $('#preview-content').html(`<div style="font-size: 60px;">📄</div><div class="file-info">${file.name}<br><small>${size}</small></div>`);
        }
        $('#file-caption').val(''); 
        $('#file-preview-modal').css('display', 'flex'); 
    }

    $('#btn-cancel-file').click(function() {
        $('#file-preview-modal').hide(); $('#widget-file').val(''); currentFile = null;
    });

    $('#btn-send-file').click(function() {
        if(!currentFile) return;
        let caption = $('#file-caption').val();
        let btn = $(this); btn.prop('disabled', true);

        let formData = new FormData();
        formData.append('session_id', localStorage.getItem('chat_session_id'));
        formData.append('message', caption); 
        formData.append('attachment', currentFile);

        sendData(formData, function() {
            $('#file-preview-modal').hide(); $('#widget-file').val(''); currentFile = null; btn.prop('disabled', false);
        });
    });

    // 3. KIRIM TEXT
    $('#form-chat').on('submit', function(e) {
        e.preventDefault();
        let msg = $('#widget-input-msg').val().trim();
        if(!msg) return;

        let formData = new FormData();
        formData.append('session_id', localStorage.getItem('chat_session_id'));
        formData.append('message', msg);
        
        $('#widget-input-msg').val(''); sendData(formData);
    });

    function sendData(formData, callback = null) {
        let token = localStorage.getItem('chat_token');

        $.ajax({
            url: API_BASE_URL + '/send_message', type: 'POST', data: formData, processData: false, contentType: false,
            headers: { "Authorization": "Bearer " + token },
            success: function() { if(callback) callback(); },
            error: function() { alert('Gagal mengirim pesan.'); if(callback) callback(); }
        });
    }

    // 4. LOGIKA WIDGET
    $('#widget-toggle').click(function() {
        $('#widget-container').fadeToggle('fast'); isWidgetOpen = !isWidgetOpen;
        if (isWidgetOpen) { unreadCount = 0; $('#notif-badge').hide().text('0'); scrollToBottom(); }
    });

    $('#widget-close').click(function() { $('#widget-container').fadeOut('fast'); isWidgetOpen = false; });
    
    $('#btn-logout').click(function(e) {
        e.preventDefault();
        
        if(confirm('Yakin ingin keluar?')) { 
            logout(); 
        }
    });

    // Auto Login
    let chatToken = localStorage.getItem('chat_token');
    let chatSessionId = localStorage.getItem('chat_session_id');

    if (chatToken && chatSessionId) {
        showChatView(); initPusher(chatSessionId); loadChatHistory();
    }

    $('#form-login').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#btn-login'); btn.text('Memproses...').prop('disabled', true);

        let loginData = new FormData();
        loginData.append('username', $('#login-username').val());
        loginData.append('password', $('#login-password').val());

        $.ajax({
            url: API_BASE_URL + '/login', type: 'POST', data: loginData, processData: false, contentType: false,
            success: function(response) {
                let res = typeof response === 'string' ? JSON.parse(response) : response;
                localStorage.setItem('chat_token', res.token);
                localStorage.setItem('chat_session_id', res.session_id);
                
                chatToken = res.token; chatSessionId = res.session_id;

                showChatView(); initPusher(res.session_id); loadChatHistory();
            },
            error: function(xhr) { alert('Login Gagal.'); btn.text('Mulai Chat').prop('disabled', false); }
        });
    });

    function loadChatHistory() {
        $.ajax({
            url: API_BASE_URL + '/get_messages', type: 'GET', data: { session_id: chatSessionId },
            headers: { "Authorization": "Bearer " + chatToken },
            success: function(data) {
                let messages = typeof data === 'string' ? JSON.parse(data) : data;
                $('#widget-messages').html('');
                messages.forEach(msg => appendMessage(msg, false));
                setTimeout(scrollToBottom, 100);
            },
            error: function(xhr) { if(xhr.status === 401) logout(); }
        });
    }

    function initPusher(sessionId) {
        const pusher = new Pusher('8d1e7ccb3c8aa557933d', { cluster: 'ap1' });
        const channel = pusher.subscribe('chat-session-' + sessionId);
        channel.bind('new-message', function(data) { appendMessage(data, true); });
    }

    function showChatView() {
        $('#widget-login-view').hide();
        $('#widget-chat-view').css('display', 'flex');
        $('#btn-logout').show();
    }

    // ==========================================
    // LOGIKA RENDER PESAN & TANGGAL
    // ==========================================
    function appendMessage(data, isNew = false) {
        let msgClass = (data.sender_type === 'user') ? 'msg-user' : 'msg-cs';
        if (isNew && data.sender_type === 'cs' && !isWidgetOpen) {
            unreadCount++; $('#notif-badge').text(unreadCount).show();
            try { document.getElementById('notif-sound').play(); } catch(e){}
        }

        let fileHtml = '';
        if (data.file_path) {
            let baseUrl = API_BASE_URL.replace('/api_chat', '');
            if (['jpg', 'jpeg', 'png'].includes(data.file_type.toLowerCase())) {
                fileHtml = `<br><img src="${baseUrl}/${data.file_path}" style="max-width: 100%; border-radius: 5px; margin-top: 5px;">`;
            } else {
                fileHtml = `<br><a href="${baseUrl}/${data.file_path}" target="_blank" style="color: blue; font-size: 11px;">📄 Lihat Dokumen</a>`;
            }
        }

        // 1. Dapatkan Objek Tanggal
        // Jika isNew (baru dikirim), gunakan waktu saat ini. Jika load history, pakai created_at DB.
        let messageDate = data.created_at ? new Date(data.created_at) : new Date();
        
        // 2. Format Waktu (Contoh: 14:30)
        let messageTime = messageDate.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        
        // 3. Format Tanggal (Contoh: Senin, 5 Maret 2026)
        let currentDateStr = messageDate.toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'});

        // 4. Pengecekan Grup Tanggal
        // Cari bubble chat terakhir dan lihat data-date nya
        let lastBubble = $('#widget-messages .msg-bubble').last();
        let lastDateStr = lastBubble.attr('data-date') || '';

        // Jika tanggal berbeda dari pesan sebelumnya, sisipkan Label Tanggal
        if (currentDateStr !== lastDateStr) {
            let dateHtml = `<div class="date-separator"><span>${currentDateStr}</span></div>`;
            $('#widget-messages').append(dateHtml);
        }

        // 5. Susun HTML Bubble dengan Jam
        let html = `
            <div class="msg-bubble ${msgClass}" data-date="${currentDateStr}">
                <div class="msg-content">${data.message || ''}${fileHtml}</div>
                <span class="msg-time">${messageTime}</span>
            </div>
        `;
        
        $('#widget-messages').append(html);
        if (isWidgetOpen) scrollToBottom();
    }

    function scrollToBottom() {
        let chatBox = $('#widget-messages')[0];
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function logout() {
        
        let token = localStorage.getItem('chat_token');

        function resetUI() {
            localStorage.removeItem('chat_token');
            localStorage.removeItem('chat_session_id');
            
            $('#widget-chat-view').hide();
            $('#btn-logout').hide(); 
            $('#widget-login-view').show();
            $('#widget-messages').html('');
            $('#btn-login').text('Mulai Chat').prop('disabled', false);
        }

        if (token) {
            $('#btn-logout').text('Keluar...').prop('disabled', true);
            
            $.ajax({
                url: API_BASE_URL + '/logout',
                type: 'POST',
                headers: { "Authorization": "Bearer " + token },
                success: function(response) {
                    console.log('Logout Berhasil!');
                },
                error: function(xhr) {
                    console.log('Logout Gagal!');
                },
                complete: function() {
                    resetUI();
                    $('#btn-logout').text('Keluar').prop('disabled', false);
                }
            });
        } else {
            resetUI(); 
        }
    }
});