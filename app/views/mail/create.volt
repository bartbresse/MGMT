<div class="body">
	<div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
		<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
		<fieldset>
			<div class="control-group" id="togroup">
                            <label class="control-label" for="to">To
                                <ul id="toerror" class="parsley-errors-list"></ul>
                            </label>
                            <div class="controls form-group input-group">
                                <div><?php echo $form->render("to"); ?></div>
                                <div class="input-group-addon"><a onclick="$('#ccgroup').show();">CC</a></div>
                                <div class="input-group-addon"><a onclick="$('#bccgroup').show();">BCC</a></div>
                            </div>
			</div>
			<div class="control-group" id="ccgroup" style="display:none;">
                            <label class="control-label" for="to">cc
                                <ul id="toerror" class="parsley-errors-list"></ul>
                            </label>
                            <div class="controls form-group">
                                <div><?php echo $form->render("to"); ?></div>
                            </div>
			</div>
			<div class="control-group" id="bccgroup" style="display:none;">
                            <label class="control-label" for="to">bcc
                                <ul id="toerror" class="parsley-errors-list"></ul>
                            </label>
                            <div class="controls form-group">
                                <div><?php echo $form->render("to"); ?></div>
                            </div>
			</div>
                    
                        <script>
                            
                             $(function() {
                                function split( val ) {
                                    return val.split( /,\s*/ );
                                }
                                function extractLast( term ) {
                                    return split( term ).pop();
                                }
                                
                                $( "#birds" )
                                    // don't navigate away from the field on tab when selecting an item
                                    .bind( "keydown", function( event ) {
                                        
                                        if ( event.keyCode === $.ui.keyCode.TAB &&
                                            $( this ).autocomplete( "instance" ).menu.active ) 
                                            {
                                                event.preventDefault();
                                            }
                                        })
                                    .autocomplete({
                                        source: function( request, response ) {
                                        $.getJSON( "search.php", {
                                        term: extractLast( request.term )
                                        }, response );
                                        },
                                        search: function() {
                                            // custom minLength
                                            var term = extractLast( this.value );
                                            
                                            if ( term.length < 2 ) 
                                            {
                                                return false;
                                            }
                                        },
                                        focus: function() {
                                            // prevent value inserted on focus
                                            return false;
                                        },
                                        select: function( event, ui ) {
                                            var terms = split( this.value );
                                            // remove the current input
                                            terms.pop();
                                            // add the selected item
                                            terms.push( ui.item.value );
                                            // add placeholder to get the comma-and-space at the end
                                            terms.push( "" );
                                            this.value = terms.join( ", " );
                                            return false;
                                        }
                                });
                            });
                            
                        </script>
                    
                    
			<div class="control-group" id="subjectgroup">
                            <label class="control-label" for="subject">Subject
                                <ul id="subjecterror" class="parsley-errors-list"></ul>
                            </label>
                            <div class="controls form-group">
                                <div><?php echo $form->render("subject"); ?></div>
                            </div>
			</div>
                    
                        <div class="control-group" id="subjectgroup">
                            <label class="control-label" for="subject">Template
                                <ul id="subjecterror" class="parsley-errors-list"></ul>
                            </label>
                            <div class="controls form-group">
                                <div><?php echo $form->render("documenttemplate"); ?></div>
                            </div>
			</div>
                    
			<div class="control-group" id="messagegroup">
                            <label class="control-label" for="message">Message
                                <ul id="messageerror" class="parsley-errors-list"></ul>
                            </label>
                            <div class="controls form-group" style="border:1px solid #ccc;">
                                <div><?php echo $form->render("message"); ?></div>
                            </div>
			</div>	
		</fieldset>                       
		<div class="form-actions" class="pull-right">
                    <button type="submit" onclick="save({class:'form-control',action:'<?=$this->url->get('mail/add');?>',goto:'<?=$this->url->get('mail/index');?>'});" class="btn btn-primary">Send</button>
                    <button type="button" onclick="go('<?=$this->url->get('mail/index');?>');" class="btn btn-default">Cancel</button>
		</div>
	</div>
</div>