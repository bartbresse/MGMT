<div class="row">
    <div class="col-md-12">
        <section class="widget">
            Data builder 
            <div id="databuilder">
                <fieldset style="width:100%;"> 
                    <div id="pagina" class="tab-pane ">
                        <script>
                            function loadEntity(o)
                            {
                                returndata = [];

                                data = o.data;
                                //data.template = o.template;
                                data.id = o.id;

                                $.ajax({
                                    url: o.action,
                                    type: 'POST',
                                    data: data,
                                    dataType:'JSON',
                                    async:false,
                                    success: function(data)
                                    { 
                                       // $('#'+o.id).html(data);
                                        returndata = data;
                                        if(typeof(data.entity) !== 'undefined')
                                        {
                                            $('#message').editable('insertHTML', data.entity+'<br />&nbsp;<br />&nbsp;'+data.table+'<br />&nbsp;<br />&nbsp; <p>&nbsp;</p>   '); 
                                        }
                                    } 
                                });
                                return returndata;
                            }
                            
                            loadform({id:'pagina-control',action:'<?=$this->url->get('databuilder/singleentityform');?>',data:{id:'pagina-control'}});
                        </script>	
                        <div id="pagina-control">
                            
                        </div>
                    </div>
                </fieldset>
            </div>
        </section>
    </div>
</div>