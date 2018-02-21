<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * @package Helpers
 * @author Ali Zandieh - 21/02/2018
 */

 
class PopulateHelper {
						

	/*
	 * The following method adds an image to the C5 site
	 * the location parameter can take an array of locations or just one.
	 * Now, we won't actually be adding to multiple pages, but rather to the master template, then we'll alias the block to the home page.
	 */
	 
	function addImage($fid, $uid, $master_template, $home_page, $area) {
		$btImage = BlockType::getByHandle('image');
		$data = array();
		$data['fID'] = $fid;
		$data['altText'] = t('Slate template');
		$data['uID'] = $uid;
		$block = $master_template->addBlock($btImage, $area, $data);
		$block->alias($home_page);
	}


	/*
	 * The following method adds a Nav to the given page and area
	 */
	
	function addNav($location, $area) {
	    $nav = BlockType::getByHandle('autonav');
	    $data = array();
	    $data['orderBy'] = 'display_asc';
	    $data['displayPages'] = 'top';
	    $data['displaySubPages'] = 'none';              
	    $location->addBlock($nav, $area, $data);
	}

	
	/*
	 * The following function adds a form block to the campaign site
	 *
	 */
	 
	function addForm($location ,$area, $question) {
		$btForm = BlockType::getByHandle('form');
		$data = array();
		$data['qsID'] = $question;
		$data['surveyName'] = t('JellyFish Form');
		$data['notifyMeOnSubmission'] = 0;
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Title'), 'inputType'=>'select', 'options'=>'Mr%%Mrs', 'position'=>1, 'required'=>1 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Firstname'), 'inputType'=>'field', 'options'=>'', 'position'=>2, 'required'=>1 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Surname'), 'inputType'=>'field', 'options'=>'', 'position'=>3 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Company Name'), 'inputType'=>'field', 'options'=>'', 'position'=>4 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Address 1'), 'inputType'=>'field', 'options'=>'', 'position'=>5 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Address 2'), 'inputType'=>'field', 'options'=>'', 'position'=>6 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Address 3'), 'inputType'=>'field', 'options'=>'', 'position'=>7 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Postcode'), 'inputType'=>'field', 'options'=>'', 'position'=>8 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Email'), 'inputType'=>'email', 'options'=>'', 'position'=>9, 'required'=>1 );
		$data['questions'][] = array( 'qsID'=>$data['qsID'], 'question'=>t('Telephone'), 'inputType'=>'field', 'options'=>'', 'position'=>10 );		
		
		if(is_array($location)) {
			foreach ($location as $set) {
				$set->addBlock($btForm, $area, $data);
			}
		} else {
			$location->addBlock($btForm, $area, $data);
		}
	}

}