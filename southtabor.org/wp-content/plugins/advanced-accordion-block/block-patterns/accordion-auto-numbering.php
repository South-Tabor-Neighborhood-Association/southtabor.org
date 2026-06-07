<?php

register_block_pattern_category(
    'advanced-accordion-block-category',
    ['label' => __('Advance Accordion Block', 'advanced-accordion-block')]
);

register_block_pattern(
    'advanced-accordion-block/pattern-accordion-auto-numbering',
    [
        'title'       => __('Accordion Auto Numbering', 'advanced-accordion-block'),
        'description' => __('A description of Pattern Five.', 'advanced-accordion-block'),
        'content'     => '<!-- wp:aab/group-accordion {"QaStyle":false,"uniqueId":"1b041763_3","defaultStyles":{"activeAccordionBorder":{"width":"1px","color":"#ebebeb","style":"solid"},"border":{"width":"1px","color":"#e3dfdf38","style":"solid"},"headingBorder":{"color":null,"style":"solid","width":"0px"},"bodyBorder":{"top":{"color":"#e3dfdf38","style":"solid","width":"1px"}},"paddings":{"top":"","right":"","left":"","bottom":""},"margins":{"top":"0px","right":"","left":"","bottom":"15px"},"borderRadius":null,"subheadingColor":null,"headingTag":"h5","headingColor":null,"headerBg":"#e3dfdf38","showHeadingIcon":false,"showIcon":true,"iconColor":null,"iconBackground":null,"bodyBg":null,"anchorLinkShow":false,"QaStyle":false,"aIconText":"A","qIconText":"Q","qIconColor":"#fff","qIconBg":"#505050","aIconColor":"#fff","aIconBg":"#f5a623","faqSchema":false,"iconBorder":{"width":"0px","style":"solid","color":"transparent"},"iconBorderRadius":"","iconFontSize":23},"autoNumbering":true} -->
<div class="custom-css-block"><style>.aagb_accordion_1b041763_3 {   }</style></div><style>
        .aagb_accordion_1b041763_3 .aagb__accordion_active .aagb__accordion_body {
            border-top: 1px solid #ebebeb;
        }
        .aagb_accordion_1b041763_3 .aagb__accordion_container.wp-block-aab-accordion-item .aagb__accordion_head {
          background-color: #e3dfdf38;
          
          border-top: none; border-right: none; border-bottom: none; border-left: none;
          
        }
        .aagb_accordion_1b041763_3 .aagb__accordion_container.wp-block-aab-accordion-item .aagb__accordion_body {
          
          
          
          border-top: 1px solid #e3dfdf38; border-right: none; border-bottom: none; border-left: none;
          
        }
        
        .aagb_accordion_1b041763_3 .wp-block-aab-accordion-item .aagb__accordion_subheading {
          margin: 5px 0 0 0;
          
        }
        .aagb_accordion_1b041763_3 .wp-block-aab-accordion-item .aagb__accordion_icon {
          
          
        }
        .aagb_accordion_1b041763_3 .wp-block-aab-accordion-item .aagb__icon {
          font-size: 23px;
        }
        .aagb_accordion_1b041763_3 .aagb__accordion_title {
          margin: 0;
          
        }
        .aagb_accordion_1b041763_3 .aagb__accordion_container {
          border: 1px solid #e3dfdf38;
          margin-top: 0px;
          margin-bottom: 15px;
          
        }
          .aagb_accordion_1b041763_3 .aagb__accordion_number{
            margin-right: 10px;
        }
        .aagb_accordion_1b041763_3 .wp-block-aab-accordion-item .aagb__accordion_subheading{
           margin-left:calc(42px + 10px) !important;
        }

        .aagb_accordion_1b041763_3 .aab-step-progress{
          background-color: #dfd2d2;
        }
            
        .aagb_accordion_1b041763_3 .aab-step-progress-bar {
          background-color: #1570ec;
        }

        .aagb_accordion_1b041763_3 .aagb__accordion_number{
          
        }
        .aagb_accordion_1b041763_3 .aab-progress-bar-container{
          background-color: #f0f0f0;
        }
        .aagb_accordion_1b041763_3 .aab-progress-bar {
            background-color: #0866ff;
        }
        
       .aagb__group_accordion_container.has_img {
            
            flex-direction: row;
        }
         .aagb_accordion_1b041763_3.wp-block-aab-group-accordion.aab-next-previous-enabled{
          padding-left: 50px !important;
            
            
            
        }
        .aagb_accordion_1b041763_3 .aagb-accordion-next-previous{
          left: 0; top: 50%; transform: translateY(-50%); position: absolute;
            
            
            
        }
        .aagb_accordion_1b041763_3 .aagb-accordion-previous-icon,.aagb_accordion_1b041763_3 .aagb-accordion-next-icon {
          background-color: #010101;
          svg{
            fill: #ffffff;
          }
        }
         
          .aagb__group_accordion_container.has_img{
          
          
          
          .aab-next-previous-enabled{
            
            
          }

        }
      
          .aagb_accordion_1b041763_3 .aagb__accordion_container {
            transition-duration: 0ms !important;
            outline: 2px solid #00000000;
          }
          .aagb_accordion_1b041763_3 .aagb__accordion_container:focus-visible {
            outline: 2px solid #C2DBFE;
          }
        </style><div class="wp-block-aab-group-accordion searchable aagb_accordion_1b041763_3 click false" id="group-accordion-1b041763_3"><!-- wp:aab/accordion-item {"progressBarBgColor":"#f0f0f0","heading":"Know Your Customers","subheading":"Understand what your customers want","readMoreText":"Read More","step":false,"stepText":"Continue","checkList":false,"button_show":false,"defaultStyles":{"activeAccordionBorder":{"width":"1px","color":"#ebebeb","style":"solid"},"border":{"width":"1px","color":"#e3dfdf38","style":"solid"},"headingBorder":{"color":null,"style":"solid","width":"0px"},"bodyBorder":{"top":{"color":"#e3dfdf38","style":"solid","width":"1px"}},"paddings":{"top":"","right":"","left":"","bottom":""},"margins":{"top":"0px","right":"","left":"","bottom":"15px"},"borderRadius":null,"subheadingColor":null,"headingTag":"h5","headingColor":null,"headerBg":"#e3dfdf38","showHeadingIcon":false,"showIcon":true,"iconColor":null,"iconBackground":null,"bodyBg":null,"anchorLinkShow":false,"QaStyle":false,"aIconText":"A","qIconText":"Q","qIconColor":"#fff","qIconBg":"#505050","aIconColor":"#fff","aIconBg":"#f5a623","faqSchema":false,"iconBorder":{"width":"0px","style":"solid","color":"transparent"},"iconBorderRadius":"","iconFontSize":23},"autoNumbering":true} -->
<div class="wp-block-aab-accordion-item aagb__accordion_container panel" data-autoplay="false" data-duration="3000" data-progress-bar-direction="horizontal" data-feature-image-url="" data-auto-numbering="true" data-progress-bar-on="false" tabindex="0"><div class="aagb__accordion_head aab_right_icon hasSubHeading " data-active="false"><div class="aagb__accordion_heading aab_right_icon aagb_right_link"><div class="head_content_wrapper"><div class="title_wrapper"><span class="aagb__accordion_number"></span><h5 class="aagb__accordion_title">Know Your Customers</h5></div><p class="aagb__accordion_subheading" placeholder="">Understand what your customers want</p></div></div><div class="aagb__accordion_icon"><div class="aagb__icon_dashicons_box"><span class="aagb__icon dashicons dashicons-plus-alt2"></span></div></div></div><div class="aagb__accordion_body   " role="region"><div class="aagb__accordion_component "><!-- wp:paragraph -->
<p>If you know what your customers like, you can give better service. Ask them what they want. Listen to their feedback. This helps your business grow fast.</p>
<!-- /wp:paragraph --></div></div></div>
<!-- /wp:aab/accordion-item -->

<!-- wp:aab/accordion-item {"progressBarBgColor":"#f0f0f0","heading":"Improve Your Product or Service","subheading":"Make your offer better than before","readMoreText":"Read More","step":false,"stepText":"Continue","checkList":false,"button_show":false,"defaultStyles":{"activeAccordionBorder":{"width":"1px","color":"#ebebeb","style":"solid"},"border":{"width":"1px","color":"#e3dfdf38","style":"solid"},"headingBorder":{"color":null,"style":"solid","width":"0px"},"bodyBorder":{"top":{"color":"#e3dfdf38","style":"solid","width":"1px"}},"paddings":{"top":"","right":"","left":"","bottom":""},"margins":{"top":"0px","right":"","left":"","bottom":"15px"},"borderRadius":null,"subheadingColor":null,"headingTag":"h5","headingColor":null,"headerBg":"#e3dfdf38","showHeadingIcon":false,"showIcon":true,"iconColor":null,"iconBackground":null,"bodyBg":null,"anchorLinkShow":false,"QaStyle":false,"aIconText":"A","qIconText":"Q","qIconColor":"#fff","qIconBg":"#505050","aIconColor":"#fff","aIconBg":"#f5a623","faqSchema":false,"iconBorder":{"width":"0px","style":"solid","color":"transparent"},"iconBorderRadius":"","iconFontSize":23},"autoNumbering":true} -->
<div class="wp-block-aab-accordion-item aagb__accordion_container panel" data-autoplay="false" data-duration="3000" data-progress-bar-direction="horizontal" data-feature-image-url="" data-auto-numbering="true" data-progress-bar-on="false" tabindex="0"><div class="aagb__accordion_head aab_right_icon hasSubHeading " data-active="false"><div class="aagb__accordion_heading aab_right_icon aagb_right_link"><div class="head_content_wrapper"><div class="title_wrapper"><span class="aagb__accordion_number"></span><h5 class="aagb__accordion_title">Improve Your Product or Service</h5></div><p class="aagb__accordion_subheading" placeholder="">Make your offer better than before</p></div></div><div class="aagb__accordion_icon"><div class="aagb__icon_dashicons_box"><span class="aagb__icon dashicons dashicons-plus-alt2"></span></div></div></div><div class="aagb__accordion_body   " role="region"><div class="aagb__accordion_component "><!-- wp:paragraph -->
<p>Always try to make your product or service better. Good quality brings more customers. If customers are happy, they will come back and tell others.</p>
<!-- /wp:paragraph --></div></div></div>
<!-- /wp:aab/accordion-item -->

<!-- wp:aab/accordion-item {"progressBarBgColor":"#f0f0f0","heading":"Use Social Media","subheading":"Promote your business online","readMoreText":"Read More","step":false,"stepText":"Continue","checkList":false,"button_show":false,"defaultStyles":{"activeAccordionBorder":{"width":"1px","color":"#ebebeb","style":"solid"},"border":{"width":"1px","color":"#e3dfdf38","style":"solid"},"headingBorder":{"color":null,"style":"solid","width":"0px"},"bodyBorder":{"top":{"color":"#e3dfdf38","style":"solid","width":"1px"}},"paddings":{"top":"","right":"","left":"","bottom":""},"margins":{"top":"0px","right":"","left":"","bottom":"15px"},"borderRadius":null,"subheadingColor":null,"headingTag":"h5","headingColor":null,"headerBg":"#e3dfdf38","showHeadingIcon":false,"showIcon":true,"iconColor":null,"iconBackground":null,"bodyBg":null,"anchorLinkShow":false,"QaStyle":false,"aIconText":"A","qIconText":"Q","qIconColor":"#fff","qIconBg":"#505050","aIconColor":"#fff","aIconBg":"#f5a623","faqSchema":false,"iconBorder":{"width":"0px","style":"solid","color":"transparent"},"iconBorderRadius":"","iconFontSize":23},"autoNumbering":true} -->
<div class="wp-block-aab-accordion-item aagb__accordion_container panel" data-autoplay="false" data-duration="3000" data-progress-bar-direction="horizontal" data-feature-image-url="" data-auto-numbering="true" data-progress-bar-on="false" tabindex="0"><div class="aagb__accordion_head aab_right_icon hasSubHeading " data-active="false"><div class="aagb__accordion_heading aab_right_icon aagb_right_link"><div class="head_content_wrapper"><div class="title_wrapper"><span class="aagb__accordion_number"></span><h5 class="aagb__accordion_title">Use Social Media</h5></div><p class="aagb__accordion_subheading" placeholder="">Promote your business online</p></div></div><div class="aagb__accordion_icon"><div class="aagb__icon_dashicons_box"><span class="aagb__icon dashicons dashicons-plus-alt2"></span></div></div></div><div class="aagb__accordion_body   " role="region"><div class="aagb__accordion_component "><!-- wp:paragraph -->
<p>Facebook, Instagram, and other social sites help your business. Post photos, updates, and videos. More people will see your business and become new customers.</p>
<!-- /wp:paragraph --></div></div></div>
<!-- /wp:aab/accordion-item -->

<!-- wp:aab/accordion-item {"progressBarBgColor":"#f0f0f0","subheading":"Make customers feel important","readMoreText":"Read More","step":false,"stepText":"Continue","checkList":false,"button_show":false,"defaultStyles":{"activeAccordionBorder":{"width":"1px","color":"#ebebeb","style":"solid"},"border":{"width":"1px","color":"#e3dfdf38","style":"solid"},"headingBorder":{"color":null,"style":"solid","width":"0px"},"bodyBorder":{"top":{"color":"#e3dfdf38","style":"solid","width":"1px"}},"paddings":{"top":"","right":"","left":"","bottom":""},"margins":{"top":"0px","right":"","left":"","bottom":"15px"},"borderRadius":null,"subheadingColor":null,"headingTag":"h5","headingColor":null,"headerBg":"#e3dfdf38","showHeadingIcon":false,"showIcon":true,"iconColor":null,"iconBackground":null,"bodyBg":null,"anchorLinkShow":false,"QaStyle":false,"aIconText":"A","qIconText":"Q","qIconColor":"#fff","qIconBg":"#505050","aIconColor":"#fff","aIconBg":"#f5a623","faqSchema":false,"iconBorder":{"width":"0px","style":"solid","color":"transparent"},"iconBorderRadius":"","iconFontSize":23},"autoNumbering":true} -->
<div class="wp-block-aab-accordion-item aagb__accordion_container panel" data-autoplay="false" data-duration="3000" data-progress-bar-direction="horizontal" data-feature-image-url="" data-auto-numbering="true" data-progress-bar-on="false" tabindex="0"><div class="aagb__accordion_head aab_right_icon hasSubHeading " data-active="false"><div class="aagb__accordion_heading aab_right_icon aagb_right_link"><div class="head_content_wrapper"><div class="title_wrapper"><span class="aagb__accordion_number"></span><h5 class="aagb__accordion_title">Accordion Heading</h5></div><p class="aagb__accordion_subheading" placeholder="">Make customers feel important</p></div></div><div class="aagb__accordion_icon"><div class="aagb__icon_dashicons_box"><span class="aagb__icon dashicons dashicons-plus-alt2"></span></div></div></div><div class="aagb__accordion_body   " role="region"><div class="aagb__accordion_component "><!-- wp:paragraph -->
<p>Always be kind and helpful to your customers. Answer their questions fast. Help them if they have problems. Good service makes customers trust you.</p>
<!-- /wp:paragraph --></div></div></div>
<!-- /wp:aab/accordion-item -->

<!-- wp:aab/accordion-item {"progressBarBgColor":"#f0f0f0","heading":"Learn from Competitors","subheading":"See what others are doing","readMoreText":"Read More","step":false,"stepText":"Continue","checkList":false,"button_show":false,"defaultStyles":{"activeAccordionBorder":{"width":"1px","color":"#ebebeb","style":"solid"},"border":{"width":"1px","color":"#e3dfdf38","style":"solid"},"headingBorder":{"color":null,"style":"solid","width":"0px"},"bodyBorder":{"top":{"color":"#e3dfdf38","style":"solid","width":"1px"}},"paddings":{"top":"","right":"","left":"","bottom":""},"margins":{"top":"0px","right":"","left":"","bottom":"15px"},"borderRadius":null,"subheadingColor":null,"headingTag":"h5","headingColor":null,"headerBg":"#e3dfdf38","showHeadingIcon":false,"showIcon":true,"iconColor":null,"iconBackground":null,"bodyBg":null,"anchorLinkShow":false,"QaStyle":false,"aIconText":"A","qIconText":"Q","qIconColor":"#fff","qIconBg":"#505050","aIconColor":"#fff","aIconBg":"#f5a623","faqSchema":false,"iconBorder":{"width":"0px","style":"solid","color":"transparent"},"iconBorderRadius":"","iconFontSize":23},"autoNumbering":true} -->
<div class="wp-block-aab-accordion-item aagb__accordion_container panel" data-autoplay="false" data-duration="3000" data-progress-bar-direction="horizontal" data-feature-image-url="" data-auto-numbering="true" data-progress-bar-on="false" tabindex="0"><div class="aagb__accordion_head aab_right_icon hasSubHeading " data-active="false"><div class="aagb__accordion_heading aab_right_icon aagb_right_link"><div class="head_content_wrapper"><div class="title_wrapper"><span class="aagb__accordion_number"></span><h5 class="aagb__accordion_title">Learn from Competitors</h5></div><p class="aagb__accordion_subheading" placeholder="">See what others are doing</p></div></div><div class="aagb__accordion_icon"><div class="aagb__icon_dashicons_box"><span class="aagb__icon dashicons dashicons-plus-alt2"></span></div></div></div><div class="aagb__accordion_body   " role="region"><div class="aagb__accordion_component "><!-- wp:paragraph -->
<p>Look at other businesses like yours. See what they are doing well. Try to do better than them. Learning from them can help your business grow faster.</p>
<!-- /wp:paragraph --></div></div></div>
<!-- /wp:aab/accordion-item --></div>
<!-- /wp:aab/group-accordion -->',
        'categories'  => ['advanced-accordion-block-category', 'premium-advanced-accordion-block'],
    ]
);
