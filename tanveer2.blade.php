        @include('include/header')
        @include('include/project_sidebar')

        <!-- body content start-->
        <div class="body-content" >

          @include('include/top_bar')

            <!-- page head start-->
            <div class="page-head">
                <h3 class="m-b-less">Add User</h3>
            </div>
            <!-- page head end-->
            

            <!--body wrapper start-->
            <div class="wrapper no-pad">

            <div class="profile-desk">
            <aside class="p-aside">


                <ul class="gallery">
                    <li>
                        <a href="#">
                            <img src="img/gallery/1.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/2.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/3.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/6.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/5.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/4.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/1.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/2.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/3.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/5.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/3.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/4.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/1.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/2.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/6.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/4.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/5.jpg" alt=""/>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="img/gallery/3.jpg" alt=""/>
                        </a>
                    </li>
                </ul>

            </aside>
            <aside class="p-short-info">
                <div class="widget">

                    <div class="form-group row gal-upload">
                        <label class="col-lg-12 control-label">Upload Image</label>
                        <div class="col-lg-12">
                            <input id="file-4" class="file" type="file" multiple=true>
                        </div>
                    </div>
                    <br/>

                    <div class="title">
                        <h1>Attachment Details</h1>
                    </div>

                    <form role="form">
                        <div class="form-group">
                            <label for="g-title">Title</label>
                            <input type="text" class="form-control" id="g-title" placeholder=" ">
                        </div>
                        <div class="form-group">
                            <label for="g-caption">Caption</label>
                            <div class="">
                                <textarea name="" class="form-control" id="g-caption" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="g-txt">Alt Text</label>
                            <input type="text" class="form-control" id="g-txt" placeholder=" ">
                        </div>
                        <div class="form-group">
                            <label for="g-desk">Description</label>
                            <div class="">
                                <textarea name="" class="form-control" id="g-desk" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="g-url">Link URL</label>
                            <input type="text" class="form-control" id="g-url" placeholder="../images/gallery/DSC_01.jpg">
                        </div>


                        <button type="submit" class="btn btn-info">Update Gallery</button>
                    </form>

                </div>

            </aside>
            </div>

            </div>
            <!--body wrapper end-->

            
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ url('/resources/assets/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ url('/resources/assets/dist/api_url.js') }}"></script>
<!-- <script src="{{ url('/resources/assets/dist/user_add.js') }}"></script> -->

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script src="{{ url('/resources/assets/js/gmaps.js') }}"></script>
<script src="{{ url('/resources/assets/js/gmaps-init.js') }}"></script>

<script>
    jQuery(document).ready(function() {
        GoogleMaps.init();
    });
</script>
@include('include/footer')