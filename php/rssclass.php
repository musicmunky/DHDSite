<?php

class RSS
{
	public function RSS()
	{
		define('LIBRARY_CHECK',true);
		define('INCLUDE_CHECK',true);
		require 'library.php';
		require 'connect.php';
	}
	
	public function GetFeed()
	{
		return $this->getItems();
	}
	  
	private function getItems()
	{
		$items = '<?xml version="1.0" encoding="UTF-8"?>
					<?xml-stylesheet type="text/xsl" media="screen" href="/~d/styles/rss2full.xsl"?>
					<?xml-stylesheet type="text/css" media="screen" href="http://feeds.feedburner.com/~d/styles/itemcontent.css"?>
					<rss xmlns:content="http://purl.org/rss/1.0/modules/content/"
						 xmlns:wfw="http://wellformedweb.org/CommentAPI/"
						 xmlns:dc="http://purl.org/dc/elements/1.1/"
						 xmlns:atom="http://www.w3.org/2005/Atom"
						 xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
						 xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
						 xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0" version="2.0">
				
						<channel>
						<title>DOGHOUSE</title>
				
						<link>http://thedoghousediaries.com</link>
						<description />
						<lastBuildDate>Mon, 14 Jul 2014 16:09:34 +0000</lastBuildDate>
						<language>en-US</language>
						<sy:updatePeriod>hourly</sy:updatePeriod>
						<sy:updateFrequency>1</sy:updateFrequency>
						<generator>http://wordpress.org/?v=3.9.1</generator>
						<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" type="application/rss+xml" href="http://feeds.feedburner.com/thedoghousediaries/feed" />
						<feedburner:info uri="thedoghousediaries/feed" />
						<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="hub" href="http://pubsubhubbub.appspot.com/" />
						<feedburner:emailServiceId>thedoghousediaries/feed</feedburner:emailServiceId>
						<feedburner:feedburnerHostname>http://feedburner.google.com</feedburner:feedburnerHostname>';

		$query = mysql_query(  "SELECT p.ID, p.post_date, p.post_content, p.post_title, GROUP_CONCAT(pm.meta_value ORDER BY pm.meta_key ASC SEPARATOR '~||~') AS metadata
								FROM posts p, postmeta pm
								WHERE post_type='post' 
									AND post_name !='' 
									AND post_status = 'publish'
									AND pm.post_id = p.ID
									AND (pm.meta_key='comic_description' OR pm.meta_key='comic_file')
								GROUP BY pm.post_id
								ORDER BY post_date DESC LIMIT 10;");

		$url = "thedoghousediaries.com";
		//$url = "198.89.125.200";
		$format = "Y-m-d H:i:s";
		while($row = mysql_fetch_assoc($query))
		{
			$date = DateTime::createFromFormat($format, $row['post_date']);
			$newdate = $date->format("D, d M Y H:i:s");
			$title = str_replace("'", "&#39;", $row['post_title']);
			$meta = explode("~||~", $row['metadata']);
			$hover = $meta[0];
			$image = $meta[1];
			
			$size = getimagesize("../dhdcomics/" . $image);
			
			//$link = "http://feedproxy.google.com/~r/thedoghousediaries/feed/~3/Qz6ypymlKOc/" . $row['ID'];
			$link = "http://" . $url . "/" . $row['ID'];
			//$img = '<img src="http://feeds.feedburner.com/~r/thedoghousediaries/feed/~4/Qz6ypymlKOc" height="1" width="1"/>';
			$img = '<img src="http://' . $url . '/dhdcomics/' . $image . '" height="1" width="1"/>';
			
			$items .= '<item>
							<title>' . $title . '</title>
							<link>' . $link . '</link>
							<comments>http://' . $url . '/' . $row['ID'] . '#comments</comments>
							<pubDate>' . $newdate . ' +0000</pubDate>
							<dc:creator><![CDATA[DOGHOUSE DIARIES]]></dc:creator>
							<category><![CDATA[Uncategorized]]></category>
							<guid isPermaLink="true">http://' . $url . '/?p=' . $row['ID'] . '</guid>
							<description><![CDATA[' . $row['post_content'] . ']]></description>
							<content:encoded>
								<![CDATA[
									<p><img src="http://' . $url . '/dhdcomics/' . $image . '" 
											width="' . $size[0] . '" height="' . $size[1] . '" 
											alt="' . $title . '" 
											title="' . $hover . '" 
											class="comic-item comic-item-' . $row['ID'] . '" />
									</p>
									<p>' . $row['post_content'] . '</p>' . $img . ']]></content:encoded>
							<wfw:commentRss>http://' . $url . '/' . $row['ID'] . '/feed</wfw:commentRss>
							<slash:comments>0</slash:comments>
							<feedburner:origLink>http://' . $url . '/' . $row['ID'] . '</feedburner:origLink>
						</item>';
		}
		$items .= '</channel></rss>';
		return $items;
	}
}
?>