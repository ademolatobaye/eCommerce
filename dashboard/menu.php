        <?php
        check_access(array('Super Admin'));
        ?>    
            
            
            
            <!-- app-Header -->
            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
                        <!-- sidebar-toggle-->
                        <a class="logo-horizontal " href="index.php">
                            <img src="assets/images/brand/logo-white.png" class="header-brand-img desktop-logo" alt="logo">
                            <img src="assets/images/brand/logo-dark.png" class="header-brand-img light-logo1"
                                alt="logo">
                        </a>
                        <!-- LOGO -->
                        <div class="main-header-center ms-3 d-none d-lg-block">
                            <form action="search.php" method="post">
                                <input type="text" class="form-control" id="typehead" placeholder="Search for products..." required name="search">
                            <button class="btn px-0 pt-2"><i class="fe fe-search" aria-hidden="true"></i></button>
                            </form>
                        </div>

                        
                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <!-- SEARCH -->
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                            </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                    <div class="d-flex order-lg-2">
                                        <div class="dropdown d-lg-none d-flex">
                                            <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                                                <i class="fe fe-search"></i>
                                            </a>
                                            <div class="dropdown-menu header-search dropdown-menu-start">
                                                <div class="input-group w-100 p-2">
                                                    <form action="search.php" method="post">
                                                        <input type="text" class="form-control" placeholder="Search...." name="search">
                                                    </form>
                                                    <div class="input-group-text btn btn-primary">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        
                                        <!-- COUNTRY -->
                                        <div class="d-flex">
                                            <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                                <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                                <span class="light-layout"><i class="fe fe-sun"></i></span>
                                            </a>
                                        </div>

                                        
                                        <div class="dropdown d-flex">
                                            <a class="nav-link icon full-screen-link nav-link-bg">
                                                <i class="fe fe-minimize fullscreen-button"></i>
                                            </a>
                                        </div>
                                        <!-- FULL-SCREEN -->
                                        
                                        <!-- SIDE-MENU -->
                                        <div class="dropdown d-flex profile-1">
                                            <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                                <img src="assets/images/users/21.jpg" alt="profile-user"
                                                    class="avatar  profile-user brround cover-image">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <div class="drop-heading">
                                                    <div class="text-center">
                                                        <h5 class="text-dark mb-0 fs-14 fw-semibold"><?php echo $session_fullname;?></h5>
                                                        <small class="text-muted"><?php echo $session_role;?></small>
                                                    </div>
                                                </div>
                                                <div class="dropdown-divider m-0"></div>
                                                <a class="dropdown-item" href="profile.php">
                                                    <i class="dropdown-icon fe fe-user"></i>My Profile
                                                </a>
                                               
                                                <a class="dropdown-item" href="signout.php" onclick="return confirm('Are you sure to sign out form this dashboard?')">
                                                    <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /app-Header -->

             <!-- PRODUCT'S REPORT BY DATE-->
    <div class="modal fade" id="productreportbydate">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Product's report by date</h6>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <form method="post" action="search.php">
                        
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Start Date</label>
                        <input type="date" class="form-control" id="recipient-name" name="startdate">
                      </div>

                      <div class="mb-3">
                        <label for="message-text" class="col-form-label">End Date</label>
                        <input type="date" class="form-control" id="recipient-name" name="enddate">
                      </div>

                      <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit" name="check">Check Product</button>
                </div>

                    </form>

                  </div>
                
            </div>
        </div>
    </div>
    <!-- END-->

     <div class="modal fade" id="newcategory">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add New Category</h6>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <form method="post" action="">
                        <?php
                        include("db_conn.php");
                        error_reporting(E_ALL);
                        if(isset($_REQUEST["addcategory"])){
                            $categoryname = trim(addslashes($_REQUEST["categoryname"]));

                // CHECKING FOR DUPLICATE RECORD
                $check = mysqli_query($conn, "SELECT * FROM category WHERE categoryname='$categoryname'");
                $checkrows = mysqli_num_rows($check);

                if($checkrows > 0){
                  echo "<script>alert('Category already.')</script>";
                }
                else{
                  // END

                  // INSERTING CATEGORY INTO DATABASE
                $sql = "INSERT INTO category(categoryname) VALUE('$categoryname')";
                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                $num = mysqli_insert_id($conn);
                if(mysqli_affected_rows($conn)!= 1){
                  $message = "Error inserting record.";
                }

                echo "<script>alert('Category successfully added.');
                window.location.href= 'view-category.php'</script>";

                        }
                        }

                        ?>
                        
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Name of Category</label>
                        <input type="text" class="form-control" id="recipient-name" name="categoryname">
                      </div>

                      <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit" name="addcategory" onclick="return confirm('Are you sure to add category?')">Add Category</button>
                </div>

                    </form>

            </div>
                
            </div>
        </div>
    </div>


     <div class="modal fade" id="newblogcategory">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add New Blog Category</h6>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <form method="post" action="">
                        <?php
                        include("db_conn.php");
                        error_reporting(E_ALL);
                        if(isset($_REQUEST["addblogcategory"])){
                            $blogcategoryname = trim(addslashes($_REQUEST["blogcategoryname"]));

                // CHECKING FOR DUPLICATE RECORD
                $check = mysqli_query($conn, "SELECT * FROM blog_category WHERE blogcategoryname='$blogcategoryname'");
                $checkrows = mysqli_num_rows($check);

                if($checkrows > 0){
                  echo "<script>alert('Blog category already exists.')</script>";
                }
                else{
                  // END

                  // INSERTING BLOG CATEGORY INTO DATABASE
                $sql = "INSERT INTO blog_category(blogcategoryname) VALUE('$blogcategoryname')";
                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                $num = mysqli_insert_id($conn);
                if(mysqli_affected_rows($conn)!= 1){
                  $message = "Error inserting record.";
                }

                echo "<script>alert('Blog category successfully added.');
                window.location.href= 'view-blog-category.php'</script>";

                        }
                        }

                        ?>
                        
                      <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Name of Category</label>
                        <input type="text" class="form-control" id="recipient-name" name="blogcategoryname">
                      </div>

                      <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit" name="addblogcategory" onclick="return confirm('Are you sure to add blog category?')">Add Category</button>
                </div>

                    </form>

            </div>
                
            </div>
        </div>
    </div>

            
 
 <!--APP-SIDEBAR-->
            <div class="sticky">
                <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                <div class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="index.php">
                            <img src="assets/images/brand/logo-white.png" class="header-brand-img desktop-logo" alt="logo">
                            <img src="assets/images/brand/icon-white.png" class="header-brand-img toggle-logo"
                                alt="logo">
                            <img src="assets/images/brand/icon-dark.png" class="header-brand-img light-logo" alt="logo">
                            <img src="assets/images/brand/logo-dark.png" class="header-brand-img light-logo1"
                                alt="logo">
                        </a>
                        <!-- LOGO -->
                    </div>

                    
                    <div class="main-sidemenu">
                        <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                                fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                            </svg></div>
                            
                        <ul class="side-menu">
                           
                            <li class="slide">
                                <a class="side-menu__item has-link" data-bs-toggle="slide" href="index.php  "><i
                                        class="side-menu__icon fe fe-home"></i><span
                                        class="side-menu__label">Dashboard</span></a>
                            </li>

                            
                          
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                        class="side-menu__icon fe fe-shield"></i><span
                                        class="side-menu__label">Staff</span><i
                                        class="angle fe fe-chevron-right"></i>
                                </a>

								<ul class="slide-menu">
									<li class="panel sidetab-menu">
										<div class="tab-menu-heading p-0 pb-2 border-0">
											<div class="tabs-menu ">
												<!-- Tabs -->
												<ul class="nav panel-tabs">
													<li><a href="#side9" class="d-flex active" data-bs-toggle="tab"><i class="fe fe-monitor me-2"></i><p>Home</p></a></li>
													<li><a href="#side10" data-bs-toggle="tab" class="d-flex"><i class="fe fe-message-square me-2"></i><p>Chat</p></a></li>
												</ul>
											</div>
										</div>
										<div class="panel-body tabs-menu-body p-0 border-0">
											<div class="tab-content">
												<div class="tab-pane active" id="side9">
													<ul class="sidemenu-list">
                                                        <li class="side-menu-label1"><a href="javascript:void(0)">Pages</a></li>
                                                        
                                                         <li><a href="register-staff.php" class="slide-item"> Register New Staff</a></li>
                                                        
                                                         <li><a href="view-staff.php" class="slide-item"> View All Staff</a></li>
                                                                                                                 
													</ul>
                                                
												</div>


											</div>
										</div>
									</li>
								</ul>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                        class="side-menu__icon fe fe-book-open"></i><span
                                        class="side-menu__label">Blog</span><i
                                        class="angle fe fe-chevron-right"></i>
                                </a>

								<ul class="slide-menu">
									<li class="panel sidetab-menu">
										<div class="tab-menu-heading p-0 pb-2 border-0">
											<div class="tabs-menu ">
												<!-- Tabs -->
												<ul class="nav panel-tabs">
													<li><a href="#side9" class="d-flex active" data-bs-toggle="tab"><i class="fe fe-monitor me-2"></i><p>Home</p></a></li>
													<li><a href="#side10" data-bs-toggle="tab" class="d-flex"><i class="fe fe-message-square me-2"></i><p>Chat</p></a></li>
												</ul>
											</div>
										</div>
										<div class="panel-body tabs-menu-body p-0 border-0">
											<div class="tab-content">
												<div class="tab-pane active" id="side9">
													<ul class="sidemenu-list">
                                                        <li class="side-menu-label1"><a href="javascript:void(0)"></a></li>
                                                         
                                                                <li><a href="add-blog.php" class="sub-slide-item"> Add New Blog</a>
                                                                </li>

                                                                <li><a href="blog.php" class="sub-slide-item"> View All Blogs</a></li>

                                                                <li><a href="view-blog-category.php" class="sub-slide-item"> View Blog Category</a></li>
                                                        
													</ul>
                                                
												</div>


											</div>
										</div>
									</li>
								</ul>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                        class="side-menu__icon fe fe-shopping-bag"></i><span
                                        class="side-menu__label">E-Commerce</span><i
                                        class="angle fe fe-chevron-right"></i>
                                </a>
								<ul class="slide-menu">
									<li class="panel sidetab-menu">
										<div class="tab-menu-heading p-0 pb-2 border-0">
											<div class="tabs-menu ">
												<!-- Tabs -->
												<ul class="nav panel-tabs">
													<li><a href="#side13" class="d-flex active" data-bs-toggle="tab"><i class="fe fe-monitor me-2"></i><p>Home</p></a></li>
													<li><a href="#side14" data-bs-toggle="tab" class="d-flex"><i class="fe fe-message-square me-2"></i><p>Chat</p></a></li>
												</ul>
											</div>
										</div>
										<div class="panel-body tabs-menu-body p-0 border-0">
											<div class="tab-content">
												<div class="tab-pane active" id="side13">
													<ul class="sidemenu-list">
                                                        <li class="side-menu-label1"><a href="javascript:void(0)">E-Commerce</a></li>


                                                        <li><a href="product.php" class="slide-item">View All Products</a></li>

                                                        <li><a href="add-product.php" class="slide-item"> Add New Product</a></li>

                                                        <li><a href="view-category.php" class="slide-item"> View Category</a></li>

                                                        <li><a href="orders.php" class="slide-item"> View All Orders</a></li>

                                                        <li class="sub-slide">
                                                            <a class="sub-side-menu__item" href="#" data-bs-toggle="modal" data-bs-target="#productreportbydate"><span
                                                                    class="sub-side-menu__label">Product's report by date</span></a>
                                                        </li>
													</ul>
                                                  
												</div>
											</div>
										</div>
									</li>
								</ul>
                            </li>

                        </ul>
                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                            </svg></div>
                    </div>
                </div>
            </div>
            <!--/APP-SIDEBAR-->