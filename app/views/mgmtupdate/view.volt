  {{ content() }}
<div class="wrap">
    <div class="content container">
        <div class="row">
            <div class="col-md-12">
<h2 class="page-title"><?php if(isset($entity->titel)){ echo $entity->titel; } ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <section class="widget">
                    <header>
                     <!--   <h4><i class="fa fa-user"></i> Account Profile <small>Create new or edit existing user</small></h4>-->
                    </header>
                    <div class="body">
                        <div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
                            <input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
                            <fieldset>

                                    
                                <div class="control-group" id="titelgroup">
                                    <label class="control-label" for="titel">Titel
                                        <ul id="titelerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->titel; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="updatecompletedgroup">
                                    <label class="control-label" for="updatecompleted">Update completed on
                                        <ul id="updatecompletederror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->updatecompleted; ?></div>
                                    </div>
                                </div>

                            </fieldset>                       
                            <div class="form-actions" class="pull-right">
                                <button type="submit" onclick="go('<?=$this->url->get('mail/new');?>?entityid=<?=$entity->id;?>&entity=<?=$mgmtentity->id;?>');" class="btn btn-primary">Send as email</button>  
                                <button type="button" onclick="go('<?=$this->url->get('mgmtupdate/index');?>');" class="btn btn-default">Terug</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>	
        </div>
    </div>		
</div>