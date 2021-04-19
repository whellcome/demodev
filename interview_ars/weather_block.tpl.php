<h2 class="category-header"><i>Fashion</i> термометр</h2>

<div class="clear"></div>


<div class="category-layout">

  <div id="container">  
    <!--Container with weather and image-->
		<section class="weather-cont">
       		<figure class="style-img">
            	<?=$items['weather_img']?>
            </figure>
            <section class="weather">
            	<div class="indicator">
                	<figure class="ico">
                    <?=$items['ico']?>
                  </figure>
                	<span><?=$items['sign'].$items['tmp']?>˚C </span>
                    <?=$items['cond']?>
                </div>
                <div class="button-block">
                	<a href="/fasionterm" title="Еще">Еще</a>
                </div>
                <section class="style-info">
                	 <?=$items['des']?>
                </section>
            </section>
		</section>
    <!--/Container with weather and image-->
    <!--Items-->
    <div class="article-items loves-items">
      <!--Column-->
      <div class="column">
      <?php
        foreach($items['loves'] as $k => $love){
          $img = theme(
            'image',
              array(
                'path' => file_load($love->field_loves_image_fid)->uri,
                'alt' => $love->title,
                'title' => $love->title,
              )
            );
          $megaclass = $love->field_show_megafeature_value==1 ? ' article-item-megafeature' : '';
          $title = interview_ars_title_nolink($love->nid, 'span');
          $lead = '<p class="lead">'.$love->field_loves_lead_value.'</p>';
          ?>
          <div class="article-item<?=$megaclass?>">
            <?=l($img.$title.$lead,'node/'.$love->nid, array('html'=>TRUE,'attributes' => array('title' => $love->title)))?>
          </div>
          <?php
          if($k>0 && ($k+1) % 3 == 0){
          ?>
            </div>
            <div class="column">
          <?php
          }
        }
      ?>
      </div>
    </div>
    <!--/Items-->
    <!--Shops-->
    <section class="shops">
      <h3>Где купить:</h3>
        <p>
        <?php
        foreach($items['loves'] as $k => $love){
          $wb = $love->field_loves_where_to_buy_value;
          print isset($wb) ? $wb . '; ' : '';
        }
        ?>
        </p>
    </section>
    <!--/Shops-->
  </div>
  <div class="clear"></div>
</div>
<div class="category-right-column">
  <?php include_once(drupal_get_path('theme', 'interviewrussia2').'/right_column.tpl.php') ?>
</div>