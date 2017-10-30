        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Document</h3>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-heading head-border">
                                Tree View
                            </div>
                            <div class="panel-body doc-list-parent">
                            <div class="loading_data" style="text-align: center; display: none;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            
<ul class="document_folder_list">
    <li><a href="" id="f1"><i class="fa fa-folder"></i> Standards</a>
        <ul class="f1">
        </ul>
    </li>
    <li><a href="" id="f2"><i class="fa fa-folder"></i> Specifications</a>
        <ul class="f2">
        </ul>
    </li>
    <li><a href="" id="f3"><i class="fa fa-folder"></i> Project Plans</a>
        <ul class="f3">
        </ul>
    </li>
    <li><a href="" id="f4"><i class="fa fa-folder"></i> Geo Technical Reports</a>
        <ul class="f4">
        </ul>
    </li>
    <li><a href="" id="f5"><i class="fa fa-folder"></i> SWPPP / WPCP</a>
        <ul class="f5">
        </ul>
    </li>
    <li><a href="" id="f6"><i class="fa fa-folder"></i> Bid Documents</a>
        <ul class="f6">
        </ul>
    </li>
    <li><a href="" id="f7"><i class="fa fa-folder"></i> Contract Items</a>
        <ul class="f7">
        </ul>
    </li>
    <li><a href="" id="f8"><i class="fa fa-folder"></i> Notice of Award</a>
        <ul class="f8">
        </ul>
    </li>
    <li><a href="" id="f9"><i class="fa fa-folder"></i> Certification of Insurance</a>
        <ul class="f9">
        </ul>
    </li>
    <li><a href="" id="f10"><i class="fa fa-folder"></i> Bonds</a>
        <ul class="f10">
        </ul>
    </li>
    <li><a href="" id="f11"><i class="fa fa-folder"></i> Contract</a>
        <ul class="f11">
        </ul>
    </li>
    <li><a href="" id="f12"><i class="fa fa-folder"></i> Notice to Proceed</a>
        <ul class="f12">
        </ul>
    </li>
    <li><a href="" id="f13"><i class="fa fa-folder"></i> Minutes of Meeting</a>
        <ul class="f13">
        </ul>
    </li>
    <li><a href="" id="f14"><i class="fa fa-folder"></i> Test Result</a>
        <ul class="f14">
        </ul>
    </li>
    <li><a href="" id="f15"><i class="fa fa-folder"></i> Pictures / Videos</a>
        <ul class="f15">
        </ul>
    </li>
    <li><a href="" id="f16"><i class="fa fa-folder"></i> Submittal</a>
        <ul class="f16">
        </ul>
    </li>
    <li><a href="" id="f17"><i class="fa fa-folder"></i> RFI</a>
        <ul class="f17">
        </ul>
    </li>
    <li><a href="" id="f18"><i class="fa fa-folder"></i> Survey</a>
        <ul class="f18">
        </ul>
    </li>
    <li><a href="" id="f19"><i class="fa fa-folder"></i> Preliminary Notice</a>
        <ul class="f19">
        </ul>
    </li>
    <li><a href="" id="f20"><i class="fa fa-folder"></i> Labor Compliance</a>
        <ul class="f20">
        </ul>
    </li>
    <li><a href="" id="f21"><i class="fa fa-folder"></i> Built Drawing</a>
        <ul class="f21">
        </ul>
    </li>
    <li><a href="" id="f22"><i class="fa fa-folder"></i> Notice Of Completion</a>
        <ul class="f22">
        </ul>
    </li>
    <li><a href="" id="f23"><i class="fa fa-folder"></i> Agency Acceptance Letter</a>
        <ul class="f23">
        </ul>
    </li>
</ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div id="alert_message"></div>
                        <div class="panel clearfix">
                            <div class="loading_data" style="text-align: center; display: none;">
                               <img src="{{ url('/resources/assets/img/loading.svg') }}" alt="" />
                            </div>
                            <div class="col-sm-8 document_detail_panel">
                                <div class="panel-body" style="padding: 15px 0px 15px 0px;">
                                    <h3 id="doc_file_name"></h3>
                                    <span style="display: inline-block; margin-bottom: 20px; text-align: center; width: 100%;" id="doc_path_value">
                                    </span>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <th scope="row">Type</th>
                                                <td id="doc_type">Adobe Acrobat PDF</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Uploaded Time:</th>
                                                <td id="doc_update_time">01/15/14 at 11:23 am</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Created by:</th>
                                                <td id="doc_created_by"><a href="">Byrd, Jessica</a> <a href="">(Murow CM)</a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-sm-4 status-panel document_detail_panel">
                                <section class="panel">
                                    <header class="panel-heading head-border nopadleft">
                                        File Status
                                    </header>
                                    <select class="form-control m-b-10" id="status">
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
                                    </select>
                                    <input type="text" id="doc_id" value="" hidden="" />
                                    <button type="button" id="save_file_status" class="btn btn-success m-b-10">Save</button>
                                </section>
                            </div>

                            <div class="col-md-12 document_detail_panel">
                                <section class="panel">
                                    <header class="panel-heading head-border nopadleft clearfix">
                                        <span class="left">User Permission</span>
                                        <a id="add_new_user_div" class="btn right btn-success adduser-toggle">Add new user</a>
                                    </header>
                                    
                                    
                                    <table class="table table-striped adduser-tbl" style="display: none;">
                                        <thead>
                                            <tr>
                                                <th>User id</th>
                                                <th>User Detail</th>
                                                <th>Position</th>
                                                <th>Company Type</th>
                                                <th>User Phone</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="user_add_permission_table">
                                        </tbody>
                                    </table>

        
                                    <div class="table-responsive m-t-20">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>User id</th>
                                                <th>User</th>
                                                <th>Permission</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="user_permission_table">
                                                <tr>
                                                    <td>1</th>
                                                    <td>AA</td>
                                                    <td>BB</td>
                                                    <td>CC</td>
                                                </tr>
                                                <tr>
                                                    <td>1</th>
                                                    <td>AA</td>
                                                    <td>BB</td>
                                                    <td>CC</td>
                                                </tr>
                                                <tr>
                                                    <td>1</th>
                                                    <td>AA</td>
                                                    <td>BB</td>
                                                    <td>CC</td>
                                                </tr>
                                                <tr>
                                                    <td>1</th>
                                                    <td>AA</td>
                                                    <td>BB</td>
                                                    <td>CC</td>
                                                </tr>
                                                <tr>
                                                    <td>1</th>
                                                    <td>AA</td>
                                                    <td>BB</td>
                                                    <td>CC</td>
                                                </tr>
                                                <tr>
                                                    <td>1</th>
                                                    <td>AA</td>
                                                    <td>BB</td>
                                                    <td>CC</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--body wrapper end-->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<script src="{{ url('/resources/assets/dist/document_view_all.js') }}"></script>
@include('include/footer')
