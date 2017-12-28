<!DOCTYPE html>
<!--[if IE 8]>          <html class="ie ie8"> <![endif]-->
<!--[if IE 9]>          <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->  <html> <!--<![endif]-->
<head>
     <!-- Current Page Styles -->
    <link rel="stylesheet" type="text/css" href="components/revolution_slider/css/settings.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="components/revolution_slider/css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="components/jquery.bxslider/jquery.bxslider.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="components/flexslider/flexslider.css" media="screen" />
    
    <!-- Header================================================== -->
    <?php include('header.php');?><!-- End Header -->
</head>
<body>
    <div id="page-wrapper">
	    <!-- Menu================================================== -->
	    <?php include('menu.php');?><!-- End Menu -->
        <div class="page-title-container">
            <div class="container">
                <div class="page-title pull-left">
                    <h2 class="entry-title">搜索结果</h2>
                </div>
                <ul class="breadcrumbs pull-right">
                    <li><a href="#">HOME</a></li>
                    <li class="active">搜索结果</li>
                </ul>
            </div>
        </div>
		<?php
			require('classes/projects.php');
			
			$developmentProjects = DevelopmentProjectRepo::getDevelopmentProjects();
		?>
        <section id="content">
            <div class="container">
                <div id="main">
                    <div class="row">
                        <div class="col-sm-4 col-md-3">
                            <h4 class="search-results-title"><i class="soap-icon-search"></i><b id="projectCount"><?php echo count($developmentProjects) ?></b> results found.</h4>
                            <div class="toggle-container filters-container">
                                <div class="panel style1 arrow-right">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#price-filter" class="collapsed">价钱</a>
                                    </h4>
                                    <div id="price-filter" class="panel-collapse collapse">
                                        <div class="panel-content">
                                            <div id="price-range"></div>
                                            <br />
                                            <span class="min-price-label pull-left"></span>
                                            <span class="max-price-label pull-right"></span>
                                            <div class="clearer"></div>
                                        </div><!-- end content -->
                                    </div>
                                </div>
                                
                                <div class="panel style1 arrow-right">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#property-type-filter" class="collapsed">财产种类</a>
                                    </h4>
                                    <div id="property-type-filter" class="panel-collapse collapse">
                                        <div class="panel-content">
                                            <ul class="check-square filters-option">
                                                <li><a href="#">全部</a></li>
                                                <li><a href="#">半独立</a></li>
                                                <li><a href="#">平房</a></li>
                                                <li><a href="#">共管</a></li>
                                                <li><a href="#">土地</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="panel style1 arrow-right">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#city-filter" class="collapsed">地点</a>
                                    </h4>
                                    <div id="city-filter" class="panel-collapse collapse">
                                        <div class="panel-content">
                                            <ul class="check-square filters-option">
												<li><a href="#">全部</a></li>
                                                <li><a href="#">吉隆坡</a></li>
                                                <li><a href="#">槟城</a></li>
												<li><a href="#">柔佛</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-9">
                            <div class="sort-by-section clearfix">
                                <ul class="swap-tiles clearfix block-sm">
                                    <li class="swap-list">
                                        <a href="project-list-view.php"><i class="soap-icon-list"></i></a>
                                    </li>
                                    <li class="swap-grid active">
                                        <a href="project-grid-view.php"><i class="soap-icon-grid"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="hotel-list listing-style3 hotel">
                            	<?php
									for ($x = 0; $x < count($developmentProjects); $x++) {
										echo '<article class="box anjuProperty" data-price="' . $developmentProjects[$x]->lowestPrice . '" data-property-type="' . $developmentProjects[$x]->propertyType . '" data-city="' . $developmentProjects[$x]->city . '">';
		                                echo '    <figure class="col-sm-5 col-md-4">';
		                                echo '        <a title="" href="ajax/slideshow-popup.html" class="hover-effect popup-gallery"><img width="270" height="160" alt="" src="images/projects/' . $developmentProjects[$x]->imageFolder . '/main.jpg"></a>';
		                                echo '    </figure>';
		                                echo '    <div class="details col-sm-7 col-md-8">';
		                                echo '        <div>';
		                                echo '            <div>';
		                                echo '                <h4 class="box-title">' . $developmentProjects[$x]->name . '<small><i class="soap-icon-departure yellow-color"></i> ' . $developmentProjects[$x]->location . '</small></h4>';
		                                echo '                <div class="amenities">';
		                                echo '                    <i class="soap-icon-wifi circle"></i>';
		                                echo '                    <i class="soap-icon-fitnessfacility circle"></i>';
		                                echo '                    <i class="soap-icon-fork circle"></i>';
		                                echo '                    <i class="soap-icon-television circle"></i>';
		                                echo '                </div>';
		                                echo '            </div>';
		                                echo '            <div>';
		                                echo '                <div class="five-stars-container">';
		                                echo '                    <span class="five-stars" style="width: 80%;"></span>';
		                                echo '                </div>';
		                                echo '                <span class="review">270 reviews</span>';
		                                echo '            </div>';
		                                echo '        </div>';
		                                echo '        <div>';
		                                echo '            <p>' . $developmentProjects[$x]->description . '</p>';
		                                echo '            <div>';
		                                echo '                <span class="price"><small>AVG/NIGHT</small>' . $developmentProjects[$x]->lowestPrice . '</span>';
		                                echo '                <a class="button btn-small full-width text-center" title="" href="project-detailed.php?id=' . $developmentProjects[$x]->id . '">SELECT</a>';
		                                echo '            </div>';
		                                echo '        </div>';
		                                echo '    </div>';
		                                echo '</article>';
									} 
						        ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer================================================== -->
    	<?php include('footer.php');?><!-- End Footer -->
    </div>
    
    <!-- Javascripts================================================== -->
    <?php include('javascripts.php');?><!-- End Javascripts -->
    
    <script type="text/javascript">
        tjq(document).ready(function() {
            tjq("#price-range").slider({
                range: true,
                min: 0,
                max: 1000,
                values: [ 100, 800 ],
                slide: function( event, ui ) {
                    tjq(".min-price-label").html(ui.values[ 0 ] + "k");
                    tjq(".max-price-label").html(ui.values[ 1 ] + "k");
                },
				stop: function(event, ui) {
					filterSelection();
				}
            });
            tjq(".min-price-label").html(tjq("#price-range").slider( "values", 0 ) + "k");
            tjq(".max-price-label").html(tjq("#price-range").slider( "values", 1 ) + "k");
			
			tjq("#property-type-filter li").click(function(){
				filterSelection();
			});
			
			tjq("#city-filter li").click(function(){
				filterSelection();
			});
        });
		
		function filterSelection(){
			var minPrice = tjq(".min-price-label").html().replace("k", "") * 1000;
			var maxPrice = tjq(".max-price-label").html().replace("k", "") * 1000;
			
			var propertyTypeArr = [];
			tjq("#property-type-filter .active").each(function(){
				propertyTypeArr.push(tjq(this).children().html());
			});
			
			var cityArr = [];
			tjq("#city-filter .active").each(function(){
				cityArr.push(tjq(this).children().html());
			});
			
			var projectCount = 0;
			tjq(".anjuProperty").each(function(){
				var thisPrice = tjq(this).attr("data-price").replace(",", "") * 1;
				var thisPropertyType = tjq(this).attr("data-property-type");
				var thisCity = tjq(this).attr("data-city");

				if(thisPrice > minPrice &&
				   thisPrice < maxPrice &&
				   (propertyTypeArr.length == 0
				   || contains(propertyTypeArr, "全部")
				   || contains(propertyTypeArr, thisPropertyType)) 
				   &&
				   (cityArr.length == 0
				   || contains(cityArr, "全部")
				   || contains(cityArr, thisCity))
				   )
				   {
					tjq(this).show();
					projectCount++;
				   }
				else
					tjq(this).hide();
			});
			
			tjq("#projectCount").html(projectCount);
		}
		
		function contains(a, obj) {
			for (var i = 0; i < a.length; i++) {
				if (a[i] === obj) {
					return true;
				}
			}
			return false;
		}
    </script>
</body>
</html>

