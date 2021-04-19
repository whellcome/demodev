<h2 class="category-header">inter<span>view</span></h2>
    
    <div class="subheader">
        <p style="text-align: center;">
            Все новое, интригующее и интересное из мира моды, искусства,<br>красоты и жизни знаменитостей – на новом канале<br>вещания Interview.
        </p>
    
        <div class="subcategory">
            <?=theme('links', array('links' => menu_navigation_links('menu-subvideo')))?>
        </div>
    </div>
    
    <div class="clear"></div>
    
    
<div class="category-layout">

<div id="container" class="category-items">
  <div class="items-wide">
  <?php
    foreach($items as $item){
      if ($item['format'] == 'megafeature'){ ?>
        <div class="article-item article-item-megafeature">
          <div class="p">
            <div class="contextual-links-region">
              <div class="mega-txt">
                <a href="../intervideo/<?=$item['nid']?>" class="video_item img_cont">
                  <?=$item['img']?>
                </a>
                <span class="inner-block-1">
                  <span class="division"><?=$item['cat']?></span>
                  <span href="../intervideo/<?=$item['nid']?>" class="video_item inner-block-2">
                    <span class="title"><i><?=$item['title1']?></i> <span><?=$item['title2']?></span></span>
                    <span class="text"><?=$item['text']?></span>
                  </span>
                </span>
              </div>
            </div>
          </div>
        </div>
  <?php }
      else{ ?>
        <div class="article-item">
          <div class="p">
            <div class="contextual-links-region">
              <div class="">
              <a href="../intervideo/<?=$item['nid']?>" class="video_item">
                <?=$item['img']?>
              </a>
              </div>
            </div>
            <?=$item['cat']?>
            <h3 class="title"><a href="../intervideo/<?=$item['nid']?>" class="video_item">
              <i><?=$item['title1']?></i> <?=$item['title2']?>
            </a></h3>
          </div>          
        </div>
  <?php }
        }
  ?>
  </div>

  <div class="items-1024">
  <?php
    foreach($items1 as $item){
      if ($item['format'] == 'megafeature'){ ?>
        <div class="article-item article-item-megafeature">
          <div class="p">
            <div class="contextual-links-region">
              <div class="mega-txt">
                <a href="../intervideo/<?=$item['nid']?>" class="video_item img_cont">
                  <?=$item['img']?>
                </a>
                <span class="inner-block-1">
                  <span class="division"><?=$item['cat']?></span>
                  <a href="../intervideo/<?=$item['nid']?>" class="video_item inner-block-2">
                    <span class="title"><i><?=$item['title1']?></i> <span><?=$item['title2']?></span></span>
                    <span class="text"><?=$item['text']?></span>
                  </a>
                </span>
              </div>
            </div>
          </div>
        </div>
  <?php }
      else{ ?>
        <div class="article-item">
          <div class="p">
            <div class="contextual-links-region">
              <div class="">
              <a href="../intervideo/<?=$item['nid']?>" class="video_item">
                <?=$item['img']?>
              </a>
              </div>
            </div>
            <?=$item['cat']?>
            <h3 class="title"><a href="../intervideo/<?=$item['nid']?>" class="video_item">
              <i><?=$item['title1']?></i> <?=$item['title2']?>
            </a></h3>
          </div>          
        </div>
  <?php }
      }
  ?>
  </div>
</div>
</div>
<div class="category-right-column">
  <?php include_once(drupal_get_path('theme', 'interviewrussia2').'/right_column.tpl.php') ?>
</div>