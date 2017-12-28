<!DOCTYPE html>
<!--[if IE 8]>          <html class="ie ie8"> <![endif]-->
<!--[if IE 9]>          <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->  <html> <!--<![endif]-->
<head>
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
                    <h2 class="entry-title">旅游</h2>
                </div>
                <ul class="breadcrumbs pull-right">
                    <li><a href="#">HOME</a></li>
                    <li><a href="#">PAGES</a></li>
                    <li class="active">Travel Guide</li>
                </ul>
            </div>
        </div>
		
		<?php
			require('classes/attractions.php');
			
			$attractions = AttractionsRepo::getAttractions();
		?>

        <section id="content">
            <div class="container">
                <div class="row">
					<div id="main" class="col-md-9">
					<?php
						for ($x = 0; $x < count($attractions); $x++) {
							echo '    <div class="tab-container style1 anjuAttractions" id="travel-guide" data-attraction-type="' . $attractions[$x]->attractionType . '" data-city="' . $attractions[$x]->city . '">';
							echo '        <div>';
							echo '			<h2 class="anjuUnderline">' . $attractions[$x]->name . '</h2>';
							echo '			<div class="anjuUnderline"></div>';
							echo '		  </div>';
							echo '        <div class="tab-content">';
							echo '            <div class="tab-pane fade active in" id="travel-guide-info">';
							echo '                <div class="image-container col-md-6">';
							echo '                    <img src="images/attractions/' . $attractions[$x]->imageFile . '" alt="">';
							echo '                </div>';
							echo '                <div class="intro table-wrapper hidden-table-sms box col-md-6">';
							echo '                    <div class="col-sm-12 col-lg-4 features table-cell">';
							echo '                        <div>';
							echo '                            <div class="col-md-3"><label>州属:</label></div><div class="col-md-9">' . $attractions[$x]->location . '</div>';
							echo '                            <div class="col-md-3"><label>门票:</label></div><div class="col-md-9">' . $attractions[$x]->priceRange . '</div>';
							echo '                            <div class="col-md-3"><label>开发时间:</label></div><div class="col-md-9">' . $attractions[$x]->operatingHours . '</div>';
							echo '                            <div class="col-md-3"><label>温馨提示:</label></div><div class="col-md-9">' . $attractions[$x]->tips . '</div>';
							echo '                        </div>';
							echo '                    </div>';
							echo '                </div>';
							echo '                <div class="main-content col-md-12">';
							echo '                    <div class="long-description">';
							echo '                        <div>';
							echo '							<h2 class="anjuUnderline">概述</h2>';
							echo '							<div class="anjuUnderline"></div>';
							echo '						</div>';
							echo '                      <div>';
							echo $attractions[$x]->description;
							echo '						</div>';
							echo '                    </div>';
							echo '                </div>';
							echo '            </div>';
							echo '        </div>';
							echo '    </div><br /><br />';
						} 
					?>
					</div>
                    <div class="sidebar col-md-3">
						<h4 class="search-results-title"><i class="soap-icon-search"></i><b id="attractionCount"><?php echo count($attractions) ?></b> results found.</h4>
						<div class="toggle-container filters-container">
							<div class="panel style1 arrow-right">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#city-filter" class="collapsed">地点</a>
								</h4>
								<div id="city-filter" class="panel-collapse collapse">
									<div class="panel-content">
										<ul class="check-square filters-option">
											<li><a href="#">全部</a></li>
											<li><a href="#">新山</a></li>
											<li><a href="#">居銮</a></li>
											<li><a href="#">雪兰莪</a></li>
											<li><a href="#">吉隆坡</a></li>
										</ul>
									</div>
								</div>
							</div>
							
							<div class="panel style1 arrow-right">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#attraction-type-filter" class="collapsed">旅游类型</a>
								</h4>
								<div id="attraction-type-filter" class="panel-collapse collapse">
									<div class="panel-content">
										<ul class="check-square filters-option">
											<li><a href="#">全部</a></li>
											<li><a href="#">亲子之旅</a></li>
											<li><a href="#">休闲之旅</a></li>
											<li><a href="#">探险之旅</a></li>
											<li><a href="#">文化之旅</a></li>
											<li><a href="#">蜜月之旅</a></li>
										</ul>
									</div>
								</div>
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
			tjq("#city-filter li").click(function(){
				filterSelection();
			});
			
			tjq("#attraction-type-filter li").click(function(){
				filterSelection();
			});
        });
		
		function filterSelection(){
			var cityArr = [];
			tjq("#city-filter .active").each(function(){
				cityArr.push(tjq(this).children().html());
			});
			
			var attractionTypeArr = [];
			tjq("#attraction-type-filter .active").each(function(){
				propertyTypeArr.push(tjq(this).children().html());
			});
			
			var attractionCount = 0;
			tjq(".anjuAttractions").each(function(){
				var thisCity = tjq(this).attr("data-city");
				var thisAttractionType = tjq(this).attr("data-attraction-type");
				
				if((cityArr.length == 0
				   || contains(cityArr, "全部")
				   || contains(cityArr, thisCity)) 
				   &&
				   (attractionTypeArr.length == 0
				   || contains(attractionTypeArr, "全部")
				   || contains(attractionTypeArr, thisAttractionType))
				   )
				   {
					tjq(this).show();
					attractionCount++;
				   }
				else
					tjq(this).hide();
			});
			
			tjq("#attractionCount").html(attractionCount);
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

