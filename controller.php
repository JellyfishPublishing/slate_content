<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
/**
 * Controller for SlateContentPackage package
 *
 * @package slate_content
 * @author Ali Zandieh - 20/02/2018 
 **/
class SlateContentPackage extends Package {
    
    protected $pkgHandle = 'slate_content';
	protected $appVersionRequired = '5.4.1.1';
	protected $pkgVersion = '0.1';
	
	/**
	 * C5 function
	 *
	 * @access public
	 * @return string
	 */
	public function getPackageDescription() {
		return t('Default content for Slate theme');
	}
	
	/**
	 * C5 function
	 *
	 * @access public
	 * @return string
	 */
	public function getPackageName() {
		return t('Slate Content');
	}
	
	/**
	 * Checks to see if the theme_slate is installed, if it's not, it'll install it
	 * Installs the default content
	 *
	 * @access public
	 * @return void
	 **/
	public function install() {
        $p = Package::getByHandle('theme_slate');
        if(is_null($p)) {
            $p = Loader::package('theme_slate');
    		try {
    			$p->install();
    		} catch(Exception $e) {
                die($e->getMessage());
    		}
        }
		// add the package to the C5 Database
		$pkg = parent::install();
		
		// variables we'll need to install content
		global $u;
		$theme = PageTheme::getByHandle('slate');
		Loader::model('collection_types');
		$page_type = CollectionType::getByHandle('two_over_three');
		$default_page_type = $page_type->getMasterTemplate();
		$home_page = Page::getById(1); 
		Loader::library("file/importer");
		$fi = new FileImporter();
		$pop = Loader::helper('populate', 'slate_content');
		$content_block_type = BlockType::getByHandle('content');
		
		$home_page->setTheme($theme);
		// OK, the home page won't have the right page type after installing the theme, so we need to change it
		$home_page->update(array('ctID' => $page_type->getCollectionTypeID()));
		
		// upload images
		$_images = array(
            'image1' => 'header.png',
            'image2' => 'slider.png',
            'image3' => 'side1.png',
            'image4' => 'side2.png',
            'footerlogo' => 'small_logo.png',
			'cta1'	 => 'cta1.jpg',
			'ctamini1' => 'small-cta-01.png',
			'ctamini2' => 'small-cta-02.png',
			'ctanav1' => 'small_CTA.png',
			'ctanav2' => 'Small_Button_1.png',
			'smallimage' => 'small_image.jpg',
        );        
        foreach($_images as $var => $image) {
            $ob = $fi->import(dirname(__FILE__) . '/images/' . $image);
            if(is_object($ob)) {
                $ob->getFile()->setUserID($u->getUserID());
                $$var = $ob;
            }
        }
        
        // add Nav
        $pop->addNav($home_page, 'Nav');
        // add images
		$pop->addImage($image1->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Header Top');
		$pop->addImage($image2->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Full Width');
		$pop->addImage($image3->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Column Two');

		$pop->addImage($cta1->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Bottom Column One');
		$pop->addImage($cta1->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Bottom Column Two');
		$pop->addImage($cta1->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Bottom Column Three');

		$pop->addImage($footerlogo->getFileID(), $u->getUserID(), $default_page_type, $home_page, 'Footer Column One');

        
        // add content
        $loremipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras in neque est. 
		Cras at blandit velit. Duis euismod, augue rhoncus ultrices molestie, eros purus placerat turpis, 
		vel cursus purus sapien dictum nulla. Quisque eleifend eros vel lectus mattis in hendrerit nisi ultrices.";

		$sample_text['content'] = t('<h1>This is the page Heading (h1)</h1><p>A standard web page should have only <strong>one</strong> H1 heading. ' . $loremipsum . '</p>
							<h2>This is a subheading (h2)</h2><p>A standard web page can have <strong>many</strong> H2 headings. ' . $loremipsum . '</p>
							<h3>This is a subheading (h3)</h3><p>A standard web page can have <strong>many</strong> H3 headings' . $loremipsum . '</p>');
		
		$content_block = $default_page_type->addBlock($content_block_type, "Column One", $sample_text);
		$content_block->alias($home_page); // the homepage has been installed, so we'll have to setup the block on the homepage (akin to doing Setup on Child pages on page type defaults)
		

		// Create 2 example pages
		$page_type = CollectionType::getByHandle('three_over_four');
		$master_page_type = $page_type->getMasterTemplate();
		$data = array();

        for ($i=1; $i < 3; $i++) { 

            $data['cName'] = "Slate Example {$i}";
            $data['cHandle'] = "slate-example-{$i}";
            $page = $home_page->add($page_type, $data);
            $page->setTheme($theme);

            $pop->addNav($page, 'Nav');

            if ($i == 1) {
	            $pop->addImage($image1->getFileID(), $u->getUserID(), $master_page_type, $page, 'Header Top');
	            $pop->addImage($image3->getFileID(), $u->getUserID(), $master_page_type, $page, 'Column Three');
	            $pop->addImage($image4->getFileID(), $u->getUserID(), $master_page_type, $page, 'Column One');

	            $pop->addImage($cta1->getFileID(), $u->getUserID(), $master_page_type, $page, 'Bottom Column One');
	            $pop->addImage($cta1->getFileID(), $u->getUserID(), $master_page_type, $page, 'Bottom Column Two');
	            $pop->addImage($cta1->getFileID(), $u->getUserID(), $master_page_type, $page, 'Bottom Column Three');  
	            $pop->addImage($cta1->getFileID(), $u->getUserID(), $master_page_type, $page, 'Bottom Column Four');        
				
				$content_block = $master_page_type->addBlock($content_block_type, "Column Two", $sample_text);
				$content_block->alias($page);
            }
        }

	}
    
}