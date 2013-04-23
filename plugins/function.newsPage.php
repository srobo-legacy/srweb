<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.newsPage.php
 * Type:     function
 * Name:     newsPage
 * Purpose:  Paginates the news feed
 * -------------------------------------------------------------
 */
require_once('config.inc.php');
require_once('createfeed.inc.php');
require_once('classes/Content.class.php');
require_once('classes/CacheWrapper.class.php');

define('ITEMS_PER_PAGE', 5);

function smarty_function_newsPage($params, &$smarty)
{

	//specify it differently because index.php's definition screws things up
	$MEMCACHE_TTL = 600;

	$p = $smarty->GetTemplateVars('p');

	//do some caching stuff
	$output = CacheWrapper::getCacheItem('[news_page_' . $p . ']', $MEMCACHE_TTL, function() use (&$p, &$smarty){

		return _getOutputForPage($p, $smarty->GetTemplateVars('base_uri'), $smarty->GetTemplateVars('root_uri'));

	});

	return $output;

}


function _getOutputForPage($p, $base_uri, $root_uri){

	$feed = getFeedContent();
	$feed = str_replace(array('<![CDATA[', ']]>'), '', $feed);

	$xml = new SimpleXMLElement($feed);
	$items = $xml->xpath('/rss/channel/item');

	for($i = ($p - 1) * ITEMS_PER_PAGE; $i < count($items) && $i < ($p - 1) * ITEMS_PER_PAGE + ITEMS_PER_PAGE; $i++){

		$item = $items[$i];

		$link = !empty($item->link) ? htmlspecialchars((string)$item->link) : '';

		$content = new Content('content/default/' . str_replace($base_uri, '', $link));
		$contentHTML = str_replace(
			array('<h3', '<h2', '<h1', '</h3', '</h2', '</h1'),
			array('<h4', '<h3', '<h2', '</h4', '</h3', '</h2'),
			$content->getParsedContent()
		);

		// Make first heading a link to the news article page
		$contentHTML = preg_replace('/<h2>(.*)<\/h2>/', '<h2><a href="' . $link . '">$1</a></h2>', $contentHTML, 1);

		$timestamp = strtotime($content->getMeta('PUB_DATE'));

		$output .= '<div class="newsItem">';

		$output .= '<div class="newsDate">' .
				'<div class="day">' . date('d', $timestamp) . '</div>' .
				'<div class="month">' . date('M', $timestamp) . '</div>' .
				'<div class="year">' . date('Y', $timestamp) . '</div>' .
			   '</div>';

		$output .= '<div class="newsContent">' . $contentHTML . '</div>';

		$output .= '<div class="newsInfo">' .
			'<a href="' . $link . '">permalink</a> | ' .
				'<a href="' . $root_uri . 'content/default/' . str_replace($base_uri, '', $link) . '">original</a>' .
			'</div>';

		$output .= '</div>';

	}//for

	$olderNewer = '<div class="clearboth">';

	$newNewsLink = '<a class="link-bottom-right" href="' . $root_uri . 'news/?p=' . (int)($p-1) . '">Newer News</a>';
	$oldNewsLink = '<a class="link-bottom-left" href="' . $root_uri . 'news/?p=' . (int)($p+1) . '">Older News</a>';

	if ($p == 1 && ITEMS_PER_PAGE < count($items)){
		$olderNewer .= $oldNewsLink;
	} elseif ($p == 1 && ITEMS_PER_PAGE >= count($items)){
		$olderNewer .= '';
	} elseif ($p > 1 && $p * ITEMS_PER_PAGE < count($items)){
		$olderNewer .= $oldNewsLink . $newNewsLink;
	} else {
		$olderNewer .= $newNewsLink;
	}

	$olderNewer .= '</div>';


	return $output . $olderNewer;

}


?>
