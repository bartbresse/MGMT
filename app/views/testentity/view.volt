  {{ content() }}
<h2><?php if(isset($entity->titel)){ echo $entity->titel; } ?></h2>

<div class="wrap">
    <div class="content container">
        <div class="row">
            <div class="col-md-12">
<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('Testentity');?></h2>
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
                                    <label class="control-label" for="titel">Testnaam
                                        <ul id="titelerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->titel; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="beschrijvinggroup">
                                    <label class="control-label" for="beschrijving">Beschrijving
                                        <ul id="beschrijvingerror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->beschrijving; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="testnummergroup">
                                    <label class="control-label" for="testnummer">Testnummer
                                        <ul id="testnummererror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->testnummer; ?></div>
                                    </div>
                                </div>
                                <div class="control-group" id="tagsgroup">
                                    <label class="control-label" for="tags">Tags
                                        <ul id="tagserror" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->tags; ?></div>
                                    </div>
                                </div>

                            </fieldset>                       
                            <div class="form-actions" class="pull-right">
                                <button type="submit" onclick="go('<?=$this->url->get('mail/new');?>?entityid=<?=$entity->id;?>&entity=<?=$mgmtentity->id;?>');" class="btn btn-primary">Send as email</button>  
                                <button type="button" onclick="go('<?=$this->url->get('Testentity/index');?>');" class="btn btn-default">Terug</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>	
        </div>
    </div>		
</div>