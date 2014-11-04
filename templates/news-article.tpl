<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>{getFromContent get="title"} | Student Robotics</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="keywords" content="{getFromContent get='keywords'}" />
	<meta name="description" content="{getFromContent get='description'}" />
	<link rel="stylesheet" type="text/css" href="{$root_uri}css/main.css" />
	<link rel="stylesheet" type="text/css" href="{$root_uri}css/news-article.css" />
	<link rel="stylesheet" type="text/css" media="print" href="{$root_uri}css/print.css" />
	<link rel="alternate" type="application/rss+xml" title="SR RSS" href="{$root_uri}feed.php" />
	<link rel="shortcut icon" href="{$root_uri}images/template/favicon.ico" />

	{include file="tracking.tpl"}
</head>

<body>
{include file="tracking-image.tpl"}
<div id="pageWrapper">

	{include file=$header_file}


	<div id="{$page_id}" class="content">
		<a class="link-top" href="{$root_uri}news">Back to News</a>

		<span class="pubdate">Published: {$pubDate|date_format:"%e %B %Y"}</span>

		{getFromContent get="content"}
		<p></p>

		<div class="clearboth">
{if !empty($prevNext->prev)}
			<a class="link-bottom-left" href="{$prevNext->prev->url}">Older: {$prevNext->prev->title|truncate:40}</a>
{/if}
{if !empty($prevNext->next)}
			<a class="link-bottom-right" href="{$prevNext->next->url}">Newer: {$prevNext->next->title|truncate:40}</a>
{/if}
		</div>

	</div>

	<div id="original">
		Original: <a href="{$root_uri}content/{$original}">{$original}</a>
	</div>


	{include file=$footer_file}

</div>

</body>

</html>

