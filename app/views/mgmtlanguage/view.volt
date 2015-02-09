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

                                    
                                <div class="control-group" id="engroup">
                                    <label class="control-label" for="en">English
                                        <ul id="enerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->en; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="nlgroup">
                                    <label class="control-label" for="nl">Dutch
                                        <ul id="nlerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->nl; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="frgroup">
                                    <label class="control-label" for="fr">French
                                        <ul id="frerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->fr; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="degroup">
                                    <label class="control-label" for="de">German
                                        <ul id="deerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->de; ?></div>
                                    </div>
                                </div>

                            </fieldset>                       
                            <div class="form-actions" class="pull-right">
                                <button type="submit" onclick="go('<?=$this->url->get('mail/new');?>?entityid=<?=$entity->id;?>&entity=<?=$mgmtentity->id;?>');" class="btn btn-primary">Send as email</button>  
                                <button type="button" onclick="go('<?=$this->url->get('mgmtlanguage/index');?>');" class="btn btn-default">Terug</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>	
        </div>
    </div>		
</div>