@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mt-20">
                <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#myModal">Upload File</button>
            </div>

            <div class="table-responsive">
                <input type="text" id="myInput" class="search-files" placeholder="Search for files..">

                <table id="myTable" class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">File</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="listing-directory-files">
                    
                  </tbody>  
                </table>
            </div>
            <div class="panel-footer-1 file-listing-page">
              <div class="row">
                  <div class="col col-xs-4 pagination-current"></div>
                  <div class="col col-xs-8">
                      <ul class="file_listing_pagination pagination hidden-xs pull-right">
                      </ul>
                     
                  </div>
              </div>
          </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload file</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="myfile">Select a file:</label>
        <input type="file" id="myfile" name="myfile">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
