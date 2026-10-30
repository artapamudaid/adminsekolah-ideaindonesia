<!-- External & Inline FullCalendar v3 CSS for maximum standalone compatibility -->
<link rel=stylesheet href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css">
<style>
  /* FullCalendar v3 Core Stylesheet (Embedded for standalone deployment) */
  /*!
 * FullCalendar v3.0.1 Stylesheet
 * Docs & License: http://fullcalendar.io/
 * (c) 2016 Adam Shaw
 */.fc-icon,body .fc{font-size:1em}.fc-button-group,.fc-icon{display:inline-block}.fc-bg,.fc-row .fc-bgevent-skeleton,.fc-row .fc-highlight-skeleton{bottom:0}.fc-icon,.fc-unselectable{-khtml-user-select:none;-webkit-touch-callout:none}.fc{direction:ltr;text-align:left}.fc-rtl{text-align:right}.fc th,.fc-basic-view td.fc-week-number,.fc-icon,.fc-toolbar{text-align:center}.fc-unthemed .fc-content,.fc-unthemed .fc-divider,.fc-unthemed .fc-list-heading td,.fc-unthemed .fc-list-view,.fc-unthemed .fc-popover,.fc-unthemed .fc-row,.fc-unthemed tbody,.fc-unthemed td,.fc-unthemed th,.fc-unthemed thead{border-color:#ddd}.fc-unthemed .fc-popover{background-color:#fff}.fc-unthemed .fc-divider,.fc-unthemed .fc-list-heading td,.fc-unthemed .fc-popover .fc-header{background:#eee}.fc-unthemed .fc-popover .fc-header .fc-close{color:#666}.fc-unthemed .fc-today{background:#fcf8e3}.fc-highlight{background:#bce8f1;opacity:.3}.fc-bgevent{background:#8fdf82;opacity:.3}.fc-nonbusiness{background:#d7d7d7}.fc-icon{height:1em;line-height:1em;overflow:hidden;font-family:"Courier New",Courier,monospace;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.fc-icon:after{position:relative}.fc-icon-left-single-arrow:after{content:"\02039";font-weight:700;font-size:200%;top:-7%}.fc-icon-right-single-arrow:after{content:"\0203A";font-weight:700;font-size:200%;top:-7%}.fc-icon-left-double-arrow:after{content:"\000AB";font-size:160%;top:-7%}.fc-icon-right-double-arrow:after{content:"\000BB";font-size:160%;top:-7%}.fc-icon-left-triangle:after{content:"\25C4";font-size:125%;top:3%}.fc-icon-right-triangle:after{content:"\25BA";font-size:125%;top:3%}.fc-icon-down-triangle:after{content:"\25BC";font-size:125%;top:2%}.fc-icon-x:after{content:"\000D7";font-size:200%;top:6%}.fc button{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;margin:0;height:2.1em;padding:0 .6em;font-size:1em;white-space:nowrap;cursor:pointer}.fc button::-moz-focus-inner{margin:0;padding:0}.fc-state-default{border:1px solid;background-color:#f5f5f5;background-image:-moz-linear-gradient(top,#fff,#e6e6e6);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));background-image:-webkit-linear-gradient(top,#fff,#e6e6e6);background-image:-o-linear-gradient(top,#fff,#e6e6e6);background-image:linear-gradient(to bottom,#fff,#e6e6e6);background-repeat:repeat-x;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25);color:#333;text-shadow:0 1px 1px rgba(255,255,255,.75);box-shadow:inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05)}.fc-state-default.fc-corner-left{border-top-left-radius:4px;border-bottom-left-radius:4px}.fc-state-default.fc-corner-right{border-top-right-radius:4px;border-bottom-right-radius:4px}.fc button .fc-icon{position:relative;top:-.05em;margin:0 .2em;vertical-align:middle}.fc-state-active,.fc-state-disabled,.fc-state-down,.fc-state-hover{color:#333;background-color:#e6e6e6}.fc-state-hover{color:#333;text-decoration:none;background-position:0 -15px;-webkit-transition:background-position .1s linear;-moz-transition:background-position .1s linear;-o-transition:background-position .1s linear;transition:background-position .1s linear}.fc-state-active,.fc-state-down{background-color:#ccc;background-image:none;box-shadow:inset 0 2px 4px rgba(0,0,0,.15),0 1px 2px rgba(0,0,0,.05)}.fc-state-disabled{cursor:default;background-image:none;opacity:.65;box-shadow:none}.fc-event.fc-draggable,.fc-event[href],.fc-popover .fc-header .fc-close,a[data-goto]{cursor:pointer}.fc .fc-button-group>*{float:left;margin:0 0 0 -1px}.fc .fc-button-group>:first-child{margin-left:0}.fc-popover{position:absolute;box-shadow:0 2px 6px rgba(0,0,0,.15)}.fc-popover .fc-header{padding:2px 4px}.fc-popover .fc-header .fc-title{margin:0 2px}.fc-ltr .fc-popover .fc-header .fc-title,.fc-rtl .fc-popover .fc-header .fc-close{float:left}.fc-ltr .fc-popover .fc-header .fc-close,.fc-rtl .fc-popover .fc-header .fc-title{float:right}.fc-unthemed .fc-popover{border-width:1px;border-style:solid}.fc-unthemed .fc-popover .fc-header .fc-close{font-size:.9em;margin-top:2px}.fc-popover>.ui-widget-header+.ui-widget-content{border-top:0}.fc-divider{border-style:solid;border-width:1px}hr.fc-divider{height:0;margin:0;padding:0 0 2px;border-width:1px 0}.fc-bg table,.fc-row .fc-bgevent-skeleton table,.fc-row .fc-highlight-skeleton table{height:100%}.fc-clear{clear:both}.fc-bg,.fc-bgevent-skeleton,.fc-helper-skeleton,.fc-highlight-skeleton{position:absolute;top:0;left:0;right:0}.fc table{width:100%;box-sizing:border-box;table-layout:fixed;border-collapse:collapse;border-spacing:0;font-size:1em}.fc td,.fc th{border-style:solid;border-width:1px;padding:0;vertical-align:top}.fc td.fc-today{border-style:double}a[data-goto]:hover{text-decoration:underline}.fc .fc-row{border-style:solid;border-width:0}.fc-row table{border-left:0 hidden transparent;border-right:0 hidden transparent;border-bottom:0 hidden transparent}.fc-row:first-child table{border-top:0 hidden transparent}.fc-row{position:relative}.fc-row .fc-bg{z-index:1}.fc-row .fc-bgevent-skeleton td,.fc-row .fc-highlight-skeleton td{border-color:transparent}.fc-row .fc-bgevent-skeleton{z-index:2}.fc-row .fc-highlight-skeleton{z-index:3}.fc-row .fc-content-skeleton{position:relative;z-index:4;padding-bottom:2px}.fc-row .fc-helper-skeleton{z-index:5}.fc-row .fc-content-skeleton td,.fc-row .fc-helper-skeleton td{background:0 0;border-color:transparent;border-bottom:0}.fc-row .fc-content-skeleton tbody td,.fc-row .fc-helper-skeleton tbody td{border-top:0}.fc-scroller{-webkit-overflow-scrolling:touch}.fc-row.fc-rigid,.fc-time-grid-event{overflow:hidden}.fc-scroller>.fc-day-grid,.fc-scroller>.fc-time-grid{position:relative;width:100%}.fc-event{position:relative;display:block;font-size:.85em;line-height:1.3;border-radius:3px;border:1px solid #3a87ad;font-weight:400}.fc-event,.fc-event-dot{background-color:#3a87ad}.fc-event,.fc-event:hover,.ui-widget .fc-event{color:#fff;text-decoration:none}.fc-not-allowed,.fc-not-allowed .fc-event{cursor:not-allowed}.fc-event .fc-bg{z-index:1;background:#fff;opacity:.25}.fc-event .fc-content{position:relative;z-index:2}.fc-event .fc-resizer{position:absolute;z-index:4;display:none}.fc-event.fc-allow-mouse-resize .fc-resizer,.fc-event.fc-selected .fc-resizer{display:block}.fc-event.fc-selected .fc-resizer:before{content:"";position:absolute;z-index:9999;top:50%;left:50%;width:40px;height:40px;margin-left:-20px;margin-top:-20px}.fc-event.fc-selected{z-index:9999!important;box-shadow:0 2px 5px rgba(0,0,0,.2)}.fc-event.fc-selected.fc-dragging{box-shadow:0 2px 7px rgba(0,0,0,.3)}.fc-h-event.fc-selected:before{content:"";position:absolute;z-index:3;top:-10px;bottom:-10px;left:0;right:0}.fc-ltr .fc-h-event.fc-not-start,.fc-rtl .fc-h-event.fc-not-end{margin-left:0;border-left-width:0;padding-left:1px;border-top-left-radius:0;border-bottom-left-radius:0}.fc-ltr .fc-h-event.fc-not-end,.fc-rtl .fc-h-event.fc-not-start{margin-right:0;border-right-width:0;padding-right:1px;border-top-right-radius:0;border-bottom-right-radius:0}.fc-ltr .fc-h-event .fc-start-resizer,.fc-rtl .fc-h-event .fc-end-resizer{cursor:w-resize;left:-1px}.fc-ltr .fc-h-event .fc-end-resizer,.fc-rtl .fc-h-event .fc-start-resizer{cursor:e-resize;right:-1px}.fc-h-event.fc-allow-mouse-resize .fc-resizer{width:7px;top:-1px;bottom:-1px}.fc-h-event.fc-selected .fc-resizer{border-radius:4px;border-width:1px;width:6px;height:6px;border-style:solid;border-color:inherit;background:#fff;top:50%;margin-top:-4px}.fc-ltr .fc-h-event.fc-selected .fc-start-resizer,.fc-rtl .fc-h-event.fc-selected .fc-end-resizer{margin-left:-4px}.fc-ltr .fc-h-event.fc-selected .fc-end-resizer,.fc-rtl .fc-h-event.fc-selected .fc-start-resizer{margin-right:-4px}.fc-day-grid-event{margin:1px 2px 0;padding:0 1px}tr:first-child>td>.fc-day-grid-event{margin-top:2px}.fc-day-grid-event.fc-selected:after{content:"";position:absolute;z-index:1;top:-1px;right:-1px;bottom:-1px;left:-1px;background:#000;opacity:.25}.fc-day-grid-event .fc-content{white-space:nowrap;overflow:hidden}.fc-day-grid-event .fc-time{font-weight:700}.fc-ltr .fc-day-grid-event.fc-allow-mouse-resize .fc-start-resizer,.fc-rtl .fc-day-grid-event.fc-allow-mouse-resize .fc-end-resizer{margin-left:-2px}.fc-ltr .fc-day-grid-event.fc-allow-mouse-resize .fc-end-resizer,.fc-rtl .fc-day-grid-event.fc-allow-mouse-resize .fc-start-resizer{margin-right:-2px}a.fc-more{margin:1px 3px;font-size:.85em;cursor:pointer;text-decoration:none}a.fc-more:hover{text-decoration:underline}.fc-limited{display:none}.fc-day-grid .fc-row{z-index:1}.fc-more-popover{z-index:2;width:220px}.fc-more-popover .fc-event-container{padding:10px}.fc-now-indicator{position:absolute;border:0 solid red}.fc-unselectable{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-tap-highlight-color:transparent}.fc-toolbar{margin-bottom:1em}.fc-toolbar .fc-left{float:left}.fc-toolbar .fc-right{float:right}.fc-toolbar .fc-center{display:inline-block}.fc .fc-toolbar>*>*{float:left;margin-left:.75em}.fc .fc-toolbar>*>:first-child{margin-left:0}.fc-toolbar h2{margin:0}.fc-toolbar button{position:relative}.fc-toolbar .fc-state-hover,.fc-toolbar .ui-state-hover{z-index:2}.fc-toolbar .fc-state-down{z-index:3}.fc-toolbar .fc-state-active,.fc-toolbar .ui-state-active{z-index:4}.fc-toolbar button:focus{z-index:5}.fc-view-container *,.fc-view-container :after,.fc-view-container :before{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box}.fc-view,.fc-view>table{position:relative;z-index:1}.fc-basicDay-view .fc-content-skeleton,.fc-basicWeek-view .fc-content-skeleton{padding-bottom:1em}.fc-basic-view .fc-body .fc-row{min-height:4em}.fc-row.fc-rigid .fc-content-skeleton{position:absolute;top:0;left:0;right:0}.fc-day-top.fc-other-month{opacity:.3}.fc-basic-view .fc-day-number,.fc-basic-view .fc-week-number{padding:2px}.fc-basic-view th.fc-day-number,.fc-basic-view th.fc-week-number{padding:0 2px}.fc-ltr .fc-basic-view .fc-day-top .fc-day-number{float:right}.fc-rtl .fc-basic-view .fc-day-top .fc-day-number{float:left}.fc-ltr .fc-basic-view .fc-day-top .fc-week-number{float:left;border-radius:0 0 3px}.fc-rtl .fc-basic-view .fc-day-top .fc-week-number{float:right;border-radius:0 0 0 3px}.fc-basic-view .fc-day-top .fc-week-number{min-width:1.5em;text-align:center;background-color:#f2f2f2;color:grey}.fc-basic-view td.fc-week-number>*{display:inline-block;min-width:1.25em}.fc-agenda-view .fc-day-grid{position:relative;z-index:2}.fc-agenda-view .fc-day-grid .fc-row{min-height:3em}.fc-agenda-view .fc-day-grid .fc-row .fc-content-skeleton{padding-bottom:1em}.fc .fc-axis{vertical-align:middle;padding:0 4px;white-space:nowrap}.fc-ltr .fc-axis{text-align:right}.fc-rtl .fc-axis{text-align:left}.ui-widget td.fc-axis{font-weight:400}.fc-time-grid,.fc-time-grid-container{position:relative;z-index:1}.fc-time-grid{min-height:100%}.fc-time-grid table{border:0 hidden transparent}.fc-time-grid>.fc-bg{z-index:1}.fc-time-grid .fc-slats,.fc-time-grid>hr{position:relative;z-index:2}.fc-time-grid .fc-content-col{position:relative}.fc-time-grid .fc-content-skeleton{position:absolute;z-index:3;top:0;left:0;right:0}.fc-time-grid .fc-business-container{position:relative;z-index:1}.fc-time-grid .fc-bgevent-container{position:relative;z-index:2}.fc-time-grid .fc-highlight-container{z-index:3;position:relative}.fc-time-grid .fc-event-container{position:relative;z-index:4}.fc-time-grid .fc-now-indicator-line{z-index:5}.fc-time-grid .fc-helper-container{position:relative;z-index:6}.fc-time-grid .fc-slats td{height:1.5em;border-bottom:0}.fc-time-grid .fc-slats .fc-minor td{border-top-style:dotted}.fc-time-grid .fc-slats .ui-widget-content{background:0 0}.fc-time-grid .fc-highlight{position:absolute;left:0;right:0}.fc-ltr .fc-time-grid .fc-event-container{margin:0 2.5% 0 2px}.fc-rtl .fc-time-grid .fc-event-container{margin:0 2px 0 2.5%}.fc-time-grid .fc-bgevent,.fc-time-grid .fc-event{position:absolute;z-index:1}.fc-time-grid .fc-bgevent{left:0;right:0}.fc-v-event.fc-not-start{border-top-width:0;padding-top:1px;border-top-left-radius:0;border-top-right-radius:0}.fc-v-event.fc-not-end{border-bottom-width:0;padding-bottom:1px;border-bottom-left-radius:0;border-bottom-right-radius:0}.fc-time-grid-event.fc-selected{overflow:visible}.fc-time-grid-event.fc-selected .fc-bg{display:none}.fc-time-grid-event .fc-content{overflow:hidden}.fc-time-grid-event .fc-time,.fc-time-grid-event .fc-title{padding:0 1px}.fc-time-grid-event .fc-time{font-size:.85em;white-space:nowrap}.fc-time-grid-event.fc-short .fc-content{white-space:nowrap}.fc-time-grid-event.fc-short .fc-time,.fc-time-grid-event.fc-short .fc-title{display:inline-block;vertical-align:top}.fc-time-grid-event.fc-short .fc-time span{display:none}.fc-time-grid-event.fc-short .fc-time:before{content:attr(data-start)}.fc-time-grid-event.fc-short .fc-time:after{content:"\000A0-\000A0"}.fc-time-grid-event.fc-short .fc-title{font-size:.85em;padding:0}.fc-time-grid-event.fc-allow-mouse-resize .fc-resizer{left:0;right:0;bottom:0;height:8px;overflow:hidden;line-height:8px;font-size:11px;font-family:monospace;text-align:center;cursor:s-resize}.fc-time-grid-event.fc-allow-mouse-resize .fc-resizer:after{content:"="}.fc-time-grid-event.fc-selected .fc-resizer{border-radius:5px;border-width:1px;width:8px;height:8px;border-style:solid;border-color:inherit;background:#fff;left:50%;margin-left:-5px;bottom:-5px}.fc-time-grid .fc-now-indicator-line{border-top-width:1px;left:0;right:0}.fc-time-grid .fc-now-indicator-arrow{margin-top:-5px}.fc-ltr .fc-time-grid .fc-now-indicator-arrow{left:0;border-width:5px 0 5px 6px;border-top-color:transparent;border-bottom-color:transparent}.fc-rtl .fc-time-grid .fc-now-indicator-arrow{right:0;border-width:5px 6px 5px 0;border-top-color:transparent;border-bottom-color:transparent}.fc-event-dot{display:inline-block;width:10px;height:10px;border-radius:5px}.fc-rtl .fc-list-view{direction:rtl}.fc-list-view{border-width:1px;border-style:solid}.fc .fc-list-table{table-layout:auto}.fc-list-table td{border-width:1px 0 0;padding:8px 14px}.fc-list-table tr:first-child td{border-top-width:0}.fc-list-heading{border-bottom-width:1px}.fc-list-heading td{font-weight:700}.fc-ltr .fc-list-heading-main{float:left}.fc-ltr .fc-list-heading-alt,.fc-rtl .fc-list-heading-main{float:right}.fc-rtl .fc-list-heading-alt{float:left}.fc-list-item.fc-has-url{cursor:pointer}.fc-list-item:hover td{background-color:#f5f5f5}.fc-list-item-marker,.fc-list-item-time{white-space:nowrap;width:1px}.fc-ltr .fc-list-item-marker{padding-right:0}.fc-rtl .fc-list-item-marker{padding-left:0}.fc-list-item-title a{text-decoration:none;color:inherit}.fc-list-item-title a[href]:hover{text-decoration:underline}.fc-list-empty-wrap2{position:absolute;top:0;left:0;right:0;bottom:0}.fc-list-empty-wrap1{width:100%;height:100%;display:table}.fc-list-empty{display:table-cell;vertical-align:middle;text-align:center}.fc-unthemed .fc-list-empty{background-color:#eee}

  /* FullCalendar Clean Modern Electric Blue Theme */
  #calendar {
    width: 100%;
    margin: 0 auto;
    font-family: inherit;
  }
  .fc-toolbar {
    margin-bottom: 15px !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }
  .fc-toolbar .fc-left {
    order: 1;
  }
  .fc-toolbar .fc-center {
    order: 2;
  }
  .fc-toolbar .fc-right {
    order: 3;
  }
  .fc-toolbar h2 {
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    margin: 0 !important;
    line-height: 1.4 !important;
  }
  .fc-button {
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #334155 !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    padding: 4px 10px !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    text-shadow: none !important;
    height: auto !important;
    outline: none !important;
    transition: all 0.2s ease !important;
  }
  .fc-button:hover {
    background: #f1f5f9 !important;
    border-color: #94a3b8 !important;
    color: #0f172a !important;
  }
  .fc-state-active, .fc-state-down {
    background: #2575FC !important;
    border-color: #2575FC !important;
    color: #ffffff !important;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15) !important;
  }
  .fc-state-disabled {
    opacity: 0.5 !important;
    cursor: not-allowed !important;
  }
  .fc-today-button {
    background: #eff6ff !important;
    color: #2575FC !important;
    border-color: #bfdbfe !important;
    font-weight: 700 !important;
  }
  .fc-day-header {
    padding: 8px 4px !important;
    background: #f8fafc !important;
    color: #64748b !important;
    font-weight: 700 !important;
    font-size: 12px !important;
    text-transform: uppercase !important;
    border-color: #e2e8f0 !important;
  }
  .fc-day {
    border-color: #e2e8f0 !important;
    transition: background 0.15s ease;
  }
  .fc-day:hover {
    background-color: #f8fafc;
  }
  .fc-today {
    background: #eff6ff !important;
    border: 1px solid #93c5fd !important;
  }
  .fc-day-number {
    padding: 6px 8px !important;
    font-weight: 600 !important;
    color: #334155 !important;
    font-size: 13px !important;
  }
  .fc-event {
    background-color: #2575FC !important;
    border-color: #1a5ac9 !important;
    border-radius: 4px !important;
    padding: 3px 6px !important;
    color: #ffffff !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 4px rgba(37, 117, 252, 0.25) !important;
    cursor: pointer !important;
    margin: 1px 2px !important;
  }
  .fc-event:hover {
    background-color: #1a5ac9 !important;
  }

  /* Modern Summary & Analytic Dashboard Styles */
  .summary-stat-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    padding: 20px;
    margin-bottom: 24px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    position: relative;
    overflow: hidden;
  }
  .summary-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(37, 117, 252, 0.08);
  }
  .summary-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #2575FC;
  }
  .summary-stat-card.card-green::before { background: #10b981; }
  .summary-stat-card.card-red::before { background: #ef4444; }
  .summary-stat-card.card-amber::before { background: #f59e0b; }
  .summary-stat-card.card-purple::before { background: #8b5cf6; }
  .summary-stat-card.card-cyan::before { background: #06b6d4; }

  .summary-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #f1f3f5;
  }
  .summary-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
  }
  .summary-card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }
  .icon-blue { background: #ebf3ff; color: #2575FC; }
  .icon-green { background: #e6f9f2; color: #10b981; }
  .icon-red { background: #fee2e2; color: #ef4444; }
  .icon-amber { background: #fef3c7; color: #f59e0b; }
  .icon-purple { background: #f3e8ff; color: #8b5cf6; }

  .stat-metric-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 13px;
    border-bottom: 1px solid #f8fafc;
  }
  .stat-metric-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
  .stat-metric-label {
    color: #64748b;
    font-weight: 500;
  }
  .stat-metric-value {
    color: #1e293b;
    font-weight: 700;
    font-family: inherit;
  }
  .stat-badge {
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-subtle-blue { background: #eff6ff; color: #1d4ed8; }
  .badge-subtle-green { background: #ecfdf5; color: #047857; }
  .badge-subtle-amber { background: #fffbeb; color: #b45309; }

  /* Modern Card Box */
  .modern-box {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
    overflow: hidden;
  }
  .modern-box-header {
    padding: 16px 20px;
    background: #ffffff;
    border-bottom: 1px solid #f1f3f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modern-box-title {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .modern-box-title i {
    color: #2575FC;
  }
  .modern-box-body {
    padding: 16px 20px;
  }
  .modern-box-footer {
    padding: 12px 20px;
    background: #f8fafc;
    border-top: 1px solid #f1f3f5;
    text-align: center;
  }
  .modern-box-footer a {
    color: #2575FC;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    transition: color 0.2s ease;
  }
  .modern-box-footer a:hover {
    color: #1a5ac9;
    text-decoration: underline;
  }

  /* Table Custom */
  .table-modern {
    width: 100%;
    margin-bottom: 0;
  }
  .table-modern th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 12px;
    border-top: none !important;
    border-bottom: 2px solid #e2e8f0 !important;
  }
  .table-modern td {
    padding: 10px 12px;
    font-size: 13px;
    color: #334155;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
  }
  .table-modern tbody tr:hover {
    background-color: #f8fafc;
  }
  .table-modern tfoot td {
    background-color: #f8fafc;
    font-weight: 700;
    color: #1e293b;
    border-top: 2px solid #e2e8f0 !important;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="font-weight: 700; color: #1e293b;">
      Analisa dan Performa
      <small style="color: #64748b; font-size: 14px;">Ringkasan Data Finansial, Siswa, dan Aktivitas Terkini</small>
    </h1>
    <ol class="breadcrumb" style="background: transparent;">
      <li><a href="<?php echo site_url('manage') ?>" style="color: #2575FC;"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Analisa dan Performa</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Top KPI Cards -->
    <div class="row">
      <!-- 1. Penerimaan KAS -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-blue">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Penerimaan KAS</h4>
            <div class="summary-card-icon icon-blue">
              <i class="fa fa-calculator"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #2575FC;">Rp <?php echo number_format((float)($total_debit_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_debit_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_debit_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 2. Pengeluaran KAS -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-red">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Pengeluaran KAS</h4>
            <div class="summary-card-icon icon-red">
              <i class="fa fa-money"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #ef4444;">Rp <?php echo number_format((float)($total_kredit_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_kredit_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_kredit_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 3. Pembayaran Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-green">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Pembayaran Siswa</h4>
            <div class="summary-card-icon icon-green">
              <i class="fa fa-bank"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #10b981;">Rp <?php echo number_format((float)($total_pay_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_pay_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_pay_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 4. Tagihan Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-amber">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Tagihan Siswa</h4>
            <div class="summary-card-icon icon-amber">
              <i class="fa fa-credit-card"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value" style="color: #d97706;">Rp <?php echo number_format((float)($tagihan_bulan_ini ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($tagihan_tahun_ini ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Status</span>
            <span class="stat-badge badge-subtle-amber"><i class="fa fa-clock-o"></i> Berjalan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Second Row KPI Cards -->
    <div class="row">
      <!-- 5. Jumlah Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-cyan">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Jumlah Siswa</h4>
            <div class="summary-card-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fa fa-graduation-cap"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Siswa Aktif</span>
            <span class="stat-metric-value" style="color: #0284c7;"><?php echo number_format((int)($total_active ?? 0), 0, ',', '.') ?> Siswa</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Alumni</span>
            <span class="stat-metric-value"><?php echo number_format((int)($total_alumni ?? 0), 0, ',', '.') ?> Siswa</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Lainnya</span>
            <span class="stat-metric-value"><?php echo number_format((int)($total_other ?? 0), 0, ',', '.') ?> Siswa</span>
          </div>
        </div>
      </div>

      <!-- 6. Jumlah Guru & Pegawai -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-green">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Guru & Pegawai</h4>
            <div class="summary-card-icon icon-green">
              <i class="fa fa-users"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Status Tetap</span>
            <span class="stat-metric-value" style="color: #059669;"><?php echo (int)($total_tetap ?? 0) ?> Orang</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tidak Tetap</span>
            <span class="stat-metric-value"><?php echo (int)($total_non_tetap ?? 0) ?> Orang</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Total Keseluruhan</span>
            <span class="stat-metric-value"><?php echo (int)($total_all ?? 0) ?> Orang</span>
          </div>
        </div>
      </div>

      <!-- 7. Tabungan Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-purple">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Tabungan Siswa</h4>
            <div class="summary-card-icon icon-purple">
              <i class="fa fa-google-wallet"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #7c3aed;">Rp <?php echo number_format((float)($total_banking_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_banking_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_banking_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 8. Masa Aktif Sistem -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-blue">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Masa Aktif Aplikasi</h4>
            <div class="summary-card-icon icon-blue">
              <i class="fa fa-calendar-check-o"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Paket Langganan</span>
            <span class="stat-badge badge-subtle-blue">Aktif Terverifikasi</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Status Server</span>
            <span class="stat-metric-value" style="color: #10b981;"><i class="fa fa-circle" style="font-size: 9px;"></i> Normal</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Waktu Sistem</span>
            <span class="stat-metric-value"><?php echo pretty_date(date('Y-m-d'), 'd F Y', false) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
      <!-- Kolom Kiri -->
      <div class="col-md-6">
        <!-- 1. Tagihan Siswa Setiap Unit -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-exclamation-circle"></i> Tagihan Siswa Setiap Unit</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Jenjang / Unit</th>
                    <th style="text-align: right;">Terlambat &le; 3 Bulan</th>
                    <th style="text-align: right;">Terlambat &gt; 3 Bulan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalA = 0;
                  $totalB = 0;
                  if (!empty($groupTagihan)) {
                    foreach ($groupTagihan as $key => $value) {
                      $val_a = (float)($value['kategori_a'] ?? 0);
                      $val_b = (float)($value['kategori_b'] ?? 0);
                      $totalA += $val_a;
                      $totalB += $val_b;
                  ?>
                    <tr>
                      <td style="font-weight: 600;"><?php echo htmlspecialchars($key) ?></td>
                      <td align="right" style="color: #d97706; font-weight: 600;">
                        Rp <?php echo number_format($val_a, 0, ',', '.') ?>
                      </td>
                      <td align="right" style="color: #ef4444; font-weight: 600;">
                        Rp <?php echo number_format($val_b, 0, ',', '.') ?>
                      </td>
                    </tr>
                  <?php 
                    }
                  } else {
                  ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted" style="padding: 20px;">Tidak ada data tagihan tertunda.</td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total</td>
                    <td align="right" style="color: #d97706;">Rp <?php echo number_format($totalA, 0, ',', '.') ?></td>
                    <td align="right" style="color: #ef4444;">Rp <?php echo number_format($totalB, 0, ',', '.') ?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/tagihan_siswa'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Detail Rekap Tagihan</a>
          </div>
        </div>

        <!-- 2. Top 5 Pemasukan per Akun -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-trophy"></i> 5 Pemasukan Terbesar per Akun</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Keterangan / Akun</th>
                    <th style="text-align: center;">Unit</th>
                    <th style="text-align: right;">Jumlah Penerimaan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if (!empty($topfive)) {
                    foreach ($topfive as $row) { 
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($row['account_description'] ?? '-') ?></td>
                      <td align="center"><span class="stat-badge badge-subtle-blue"><?php echo htmlspecialchars($row['majors_short_name'] ?? '-') ?></span></td>
                      <td align="right" style="color: #10b981; font-weight: 600;">Rp <?php echo number_format((float)($row['total_deb'] ?? 0), 0, ',', '.') ?></td>
                    </tr>
                  <?php 
                    }
                  } else {
                  ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted" style="padding: 20px;">Belum ada riwayat transaksi penerimaan pada periode ini.</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/pemasukan_per_akun'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Semua Pemasukan per Akun</a>
          </div>
        </div>

        <!-- 3. Status Guru & Pegawai -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-id-badge"></i> Rekap Status Guru & Pegawai</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Kategori Status</th>
                    <th style="text-align: right;">Jumlah Orang</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Guru & Pegawai Tetap</td>
                    <td align="right" style="font-weight: 600; color: #10b981;"><?php echo (int)($total_tetap ?? 0) ?> Orang</td>
                  </tr>
                  <tr>
                    <td>Guru & Pegawai Tidak Tetap / Kontrak</td>
                    <td align="right" style="font-weight: 600; color: #f59e0b;"><?php echo (int)($total_non_tetap ?? 0) ?> Orang</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total Guru & Pegawai Terdaftar</td>
                    <td align="right"><?php echo (int)($total_all ?? 0) ?> Orang</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/total_guru_pegawai'); ?>"><i class="fa fa-arrow-circle-right"></i> Kelola Data Guru & Pegawai</a>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="col-md-6">
        <!-- 4. Jurnal Keuangan -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-balance-scale"></i> Jurnal Keuangan</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Komponen Jurnal</th>
                    <th style="text-align: right;">Bulan Ini</th>
                    <th style="text-align: right;">Tahun Ajaran Ini</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Penerimaan Kas</td>
                    <td align="right" style="color: #10b981; font-weight: 600;">Rp <?php echo number_format((float)($total_debit_month ?? 0), 0, ',', '.') ?></td>
                    <td align="right" style="color: #10b981; font-weight: 600;">Rp <?php echo number_format((float)($total_debit_period ?? 0), 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>Pengeluaran Kas</td>
                    <td align="right" style="color: #ef4444; font-weight: 600;">Rp <?php echo number_format((float)($total_kredit_month ?? 0), 0, ',', '.') ?></td>
                    <td align="right" style="color: #ef4444; font-weight: 600;">Rp <?php echo number_format((float)($total_kredit_period ?? 0), 0, ',', '.') ?></td>
                  </tr>
                </tbody>
                <tfoot>
                  <?php 
                    $saldo_bulan = (float)($total_debit_month ?? 0) - (float)($total_kredit_month ?? 0);
                    $saldo_tahun = (float)($total_debit_period ?? 0) - (float)($total_kredit_period ?? 0);
                  ?>
                  <tr>
                    <td>Saldo Bersih</td>
                    <td align="right" style="color: <?php echo $saldo_bulan >= 0 ? '#2575FC' : '#ef4444' ?>;">
                      Rp <?php echo number_format($saldo_bulan, 0, ',', '.') ?>
                    </td>
                    <td align="right" style="color: <?php echo $saldo_tahun >= 0 ? '#2575FC' : '#ef4444' ?>;">
                      Rp <?php echo number_format($saldo_tahun, 0, ',', '.') ?>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/jurnal_keuangan'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Laporan Jurnal Lengkap</a>
          </div>
        </div>

        <!-- 5. Laporan KAS Setiap Unit -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-building-o"></i> Laporan KAS Setiap Unit</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Unit Pendidikan</th>
                    <th style="text-align: right;">Bulan Ini</th>
                    <th style="text-align: right;">Tahun Ajaran Ini</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $subKasBulan = 0;
                  $subKasTahun = 0;
                  if (!empty($kasUnit)) {
                    foreach ($kasUnit as $key => $kas) {
                      $val_kas_bulan = (float)($kas['bulan'] ?? 0);
                      $val_kas_tahun = (float)($kas['tahun'] ?? 0);
                      $subKasBulan += $val_kas_bulan;
                      $subKasTahun += $val_kas_tahun;
                  ?>
                    <tr>
                      <td style="font-weight: 600;"><?php echo htmlspecialchars($key) ?></td>
                      <td align="right">Rp <?php echo number_format($val_kas_bulan, 0, ',', '.') ?></td>
                      <td align="right" style="font-weight: 600; color: #2575FC;">Rp <?php echo number_format($val_kas_tahun, 0, ',', '.') ?></td>
                    </tr>
                  <?php 
                    }
                  } else {
                  ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted" style="padding: 20px;">Data kas unit belum tersedia.</td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total Penerimaan Unit</td>
                    <td align="right">Rp <?php echo number_format($subKasBulan, 0, ',', '.') ?></td>
                    <td align="right" style="color: #2575FC;">Rp <?php echo number_format($subKasTahun, 0, ',', '.') ?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/laporan_kas'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Semua Rincian Kas</a>
          </div>
        </div>

        <!-- 6. Jumlah Siswa Setiap Jenjang -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-users"></i> Distribusi Siswa per Jenjang</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Jenjang / Unit</th>
                    <th style="text-align: center;">Siswa Aktif</th>
                    <th style="text-align: center;">Total Seluruhnya</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $sumAktif = 0;
                  $sumAll = 0;
                  if (!empty($majors)) {
                    foreach ($majors as $major) { 
                      $studentActive = 0;
                      $studentAll = 0;
                      if (!empty($student)) {
                        foreach ($student as $stud) {
                          if ($major['majors_id'] == $stud['majors_majors_id']) {
                            $studentAll++;
                            if (($stud['student_status'] ?? 0) == 1) {
                              $studentActive++;
                            }
                          }
                        }
                      }
                      $sumAktif += $studentActive;
                      $sumAll += $studentAll;
                  ?>
                    <tr>
                      <td style="font-weight: 600;"><?php echo htmlspecialchars($major['majors_short_name']) ?></td>
                      <td align="center"><span class="stat-badge badge-subtle-green"><?php echo $studentActive ?> Siswa</span></td>
                      <td align="center"><span class="stat-badge badge-subtle-blue"><?php echo $studentAll ?> Siswa</span></td>
                    </tr>
                  <?php 
                    }
                  } 
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total Siswa</td>
                    <td align="center"><?php echo $sumAktif ?> Siswa</td>
                    <td align="center"><?php echo $sumAll ?> Siswa</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/jumlah_siswa'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Detail Data Siswa</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Informasi dan Kalender Row -->
    <div class="row">
      <div class="col-md-6">
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-bullhorn"></i> Pengumuman & Informasi</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <tbody>
                  <?php 
                  if (!empty($information)) {
                    foreach ($information as $info) : 
                  ?>
                    <tr>
                      <td style="width: 70px;">
                        <?php if (!empty($info['information_img'])) { ?>
                          <img src="<?php echo upload_url('information/' . $info['information_img']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        <?php } else { ?>
                          <div style="width: 50px; height: 50px; background: #eff6ff; color: #2575FC; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-info-circle"></i>
                          </div>
                        <?php } ?>
                      </td>
                      <td>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;"><?php echo htmlspecialchars($info['information_title']) ?></div>
                        <small style="color: #94a3b8;"><i class="fa fa-clock-o"></i> <?php echo pretty_date($info['information_input_date'] ?? date('Y-m-d'), 'd F Y', false) ?></small>
                      </td>
                    </tr>
                  <?php 
                    endforeach; 
                  } else {
                  ?>
                    <tr>
                      <td colspan="2" class="text-center text-muted" style="padding: 24px;">Belum ada pengumuman yang dipublikasikan.</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/information'); ?>"><i class="fa fa-arrow-circle-right"></i> Kelola Informasi</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-calendar"></i> Kalender Agenda & Hari Libur</h3>
          </div>
          <div class="modern-box-body">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

<!-- Modal Tambah Agenda -->
<div class="modal fade in" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <?php echo form_open(current_url()); ?>
    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header" style="background: #2575FC; color: #ffffff;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addModalLabel" style="font-weight: 600;"><i class="fa fa-plus-circle"></i> Tambah Agenda / Hari Libur</h4>
      </div>
      <div class="modal-body" style="padding: 20px;">
        <input type="hidden" name="add" value="1">
        <div class="form-group">
          <label style="font-weight: 600; color: #475569;">Tanggal Terpilih</label>
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-weight: 700; color: #2575FC;" id="labelDate">-</div>
          <input type="hidden" name="date" class="form-control" id="inputDate">
        </div>
        <div class="form-group">
          <label style="font-weight: 600; color: #475569;">Keterangan Agenda*</label>
          <textarea name="info" id="inputDesc" class="form-control" rows="3" placeholder="Masukkan judul agenda atau keterangan libur..." style="border-radius: 8px;"></textarea>
        </div>
      </div>
      <div class="modal-footer" style="background: #f8fafc;">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
        <button type="submit" id="btnSimpan" class="btn btn-primary disabled" style="background: #2575FC; border-color: #2575FC; border-radius: 8px;">Simpan Agenda</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- Modal Hapus Agenda -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <?php echo form_open(current_url()); ?>
    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header" style="background: #ef4444; color: #ffffff;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delModalLabel" style="font-weight: 600;"><i class="fa fa-trash"></i> Hapus Agenda</h4>
      </div>
      <div class="modal-body" style="padding: 20px;">
        <input type="hidden" name="del" value="1">
        <input type="hidden" name="id" id="idDel">
        <p style="color: #64748b; margin-bottom: 15px;">Apakah Anda yakin ingin menghapus agenda kegiatan berikut?</p>
        <div class="well well-sm" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
          <div style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;">Tanggal: <span id="showDate" style="color: #1e293b; font-weight: 600;"></span></div>
          <div style="font-size: 14px; color: #1e293b; font-weight: 700;" id="showDesc">-</div>
        </div>
      </div>
      <div class="modal-footer" style="background: #f8fafc;">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
        <button type="submit" class="btn btn-danger" style="background: #ef4444; border-color: #ef4444; border-radius: 8px;">Hapus</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'prevYear,nextYear'
      },
      buttonText: {
        today: 'Hari Ini',
        prevYear: '« Thn',
        nextYear: 'Thn »'
      },
      events: "<?php echo site_url('manage/summary/get'); ?>",
      dayClick: function(date, jsEvent, view) {
        var fullDate = (typeof date.format === 'function') ? date.format('YYYY-MM-DD') : (date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2));

        $('#addModal').modal('show');
        $("#inputDate").val(fullDate);
        $("#labelDate").text(fullDate);
      },
      eventClick: function(calEvent, jsEvent, view) {
        $("#delModal").modal('show');
        $("#idDel").val(calEvent.id);
        var formattedDate = '';
        if (calEvent.start) {
          if (typeof calEvent.start.format === 'function') {
            formattedDate = calEvent.start.format('DD/MM/YYYY');
          } else if (calEvent.start instanceof Date) {
            formattedDate = ('0' + calEvent.start.getDate()).slice(-2) + '/' + ('0' + (calEvent.start.getMonth() + 1)).slice(-2) + '/' + calEvent.start.getFullYear();
          }
        }
        $("#showDate").text(formattedDate);
        $("#showDesc").text(calEvent.title || '-');
      }
    });

    setTimeout(function() {
      $('#calendar').fullCalendar('render');
    }, 200);

    $("#inputDesc").on('change keyup focus input propertychange', function() {
      var desc = $("#inputDesc").val();
      if (desc && desc.trim().length > 0) {
        $("#btnSimpan").removeClass('disabled');
      } else {
        $("#btnSimpan").addClass('disabled');
      }
    });
  });
</script>