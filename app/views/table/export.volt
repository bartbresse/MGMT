<?  if($auth['clearance'] > 800){ ?>		
<div>
    <?/*
    <select id="export-options">
            <option value="csv">Exporteren als CSV</option>
    </select>
     <button class="btn btn-danger export-button">
            <i class="fa fa-eject"></i>
            Go
    </button>
    */?>
    
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".export-modal">export</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".email-modal">email</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".print-modal">print</button>
    
    <div class="modal fade export-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Modal title</h4>
        </div>
        <div class="modal-content">
            export
        </div>
      </div>
    </div>
    
    <div class="modal fade email-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Modal title</h4>
        </div>
        <div class="modal-content">
            email 
        </div>
      </div>
    </div>
    
    <div class="modal fade print-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Modal title</h4>
        </div>
        <div class="modal-content">
            print
        </div>
      </div>
    </div>
    
</div>
<? }  ?>