<div class="wrap">
 <div class="content container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Email client<small></small></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
			<section class="widget">
                <header>
                    <h4>
                        Inbox App
                    </h4>
                </header>
                <div class="body">
                    <div id="mailbox-app" class="mailbox">
                        <div class="row">
                            <div class="col-sm-2">
                                <button id="compose-btn" class="btn btn-danger btn-block">Compose</button>
                                <ul id="folders-list" class="mailbox-folders">
									<!--
									<li class="active">
										<a href="#">Inbox</a>
									</li>
									-->
									<li class="">
										<a href="#">Sent</a>
									</li>
									<li class="">
										<a href="#">Drafts</a>
									</li>
									<li class="">
										<a href="#">Starred</a>
									</li>
								</ul>
                            </div>
                            <div class="col-sm-10">
                                <h2 id="folder-title" class="folder-title">Inbox <small>(2 unread messages)</small></h2>
                                <form class="form-inline form-search text-align-right" action="">
                                    <label>
                                        <input id="mailbox-search" class="input-search" placeholder="Search folder..." type="search">
                                    </label>
                                </form>
                                <div id="mailbox-content" class="mailbox-content"><table class="folder-view table table-striped" id="folder-view">
    
        <thead>
        <tr>
            <th colspan="3" id="folder-actions">
    <div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" id="toggle-all" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div>
    <span class="btn-group">
        <a class="btn btn-default btn-sm" href="#" data-toggle="dropdown">
            Select
            <i class="fa fa-angle-down "></i>
        </a>
        <ul class="dropdown-menu">
            <li><a id="select-all" href="#">All</a></li>
            <li><a id="select-none" href="#">None</a></li>
            <li class="divider"></li>
            <li><a id="select-read" href="#">Read</a></li>
            <li><a id="select-unread" href="#">Unread</a></li>
        </ul>
    </span>
    
</th>
            <th colspan="3" class="total-pages">
                1-30 of 1,421
                <button class="btn btn-default btn-xs">
                    <i class="fa fa-angle-left fa-lg"></i>
                </button>
                <button class="btn btn-default btn-xs">
                    <i class="fa fa-angle-right fa-lg"></i>
                </button>
            </th>
        </tr>
        </thead>
    <tbody>
	
	
		<!--
        <tr class="unread">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star color-orange"></i></span></td>
			<td class="name hidden-xs">Philip Horbacheuski</td>
			<td class="subject">Hi, Welcome to Google Mail</td>
			<td class="tiny-column"><i class="fa fa-paper-clip"></i></td>
			<td class="date">12:50</td>
		</tr>
		<tr class="unread">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star-o"></i></span></td>
			<td class="name hidden-xs">StackExchange</td>
			<td class="subject">New Python questions for this week!</td>
			<td class="tiny-column"><i class="fa fa-paper-clip"></i></td>
			<td class="date">Aug 14</td>
		</tr>
		<tr class="">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star-o"></i></span></td>
			<td class="name hidden-xs">notifications@facebook.com</td>
			<td class="subject">Someone just commented on your photo!</td>
			<td class="tiny-column"></td>
			<td class="date">Aug 7</td>
		</tr>
		<tr class="">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star color-orange"></i></span></td>
			<td class="name hidden-xs">Twitter</td>
			<td class="subject">@hackernews is now following you on Twitter</td>
			<td class="tiny-column"></td>
			<td class="date">Jul 31</td>
		</tr>
		<tr class="">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star-o"></i></span></td>
			<td class="name hidden-xs">Nikola Foley</td>
			<td class="subject">Quiet led own cause three him</td>
			<td class="tiny-column"><i class="fa fa-paper-clip"></i></td>
			<td class="date">Jul 22</td>
		</tr>
		<tr class="">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star-o"></i></span></td>
			<td class="name hidden-xs">Ernst Hardy</td>
			<td class="subject">Raising say express had chiefly detract demands she</td>
			<td class="tiny-column"></td>
			<td class="date">Jul 15</td>
		</tr>
		<tr class="">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star-o"></i></span></td>
			<td class="name hidden-xs">LinkedIn</td>
			<td class="subject">Jobs you may be interested in</td>
			<td class="tiny-column"></td>
			<td class="date">Jul 12</td>
		</tr>
		<tr class="">
			<td class="tiny-column"><div style="position: relative;" class="icheckbox_square-grey"><input style="position: absolute; opacity: 0;" class="selected-checkbox" type="checkbox"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div></td>
			<td class="tiny-column"><span class="starred"><i class="fa fa-star color-orange"></i></span></td>
			<td class="name hidden-xs">Naevius Victorsson</td>
			<td class="subject">Front no party young abode state up</td>
			<td class="tiny-column"></td>
			<td class="date">Jul 11</td>
		</tr>
		-->
		</tbody>
    
</table></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
			
			
			</div>
        </div>
    </div>
</div>

</div>