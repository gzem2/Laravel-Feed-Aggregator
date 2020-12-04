<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Models\FeedItem;


class SiteController extends Controller
{
    public function index()
    {
        $feeds = Feed::all();

        if ($feeds->isEmpty()) {
            return view('feed', [
                'feeds' => json_encode([]),
                'feed_items' => json_encode([[
                    'feed_id' => 0,
                    'title' => "There are no RSS feeds added yet",
                    'link' => '',
                    'description' => '
                     Example RSS feeds:<br><br>
                     Google News:<br>
                     https://news.google.com/rss?topic=h&hl=en-US&gl=US&ceid=US:en <br><br>
                     NASA News:<br>
                       https://www.nasa.gov/rss/dyn/breaking_news.rss <br><br>
                     ',
                    'source' => '',
                    'source_url' => ''
                ]]),
                'title' => "No RSS feed",
                'link' => "",
                'active' => 0
            ]);
        }

        $feed = $feeds->last();

        $feed_items = FeedItem::where('feed_id', $feed->id)->orderBy('pubDate', 'desc')->offset(0)->limit(10)->get();

        return view('feed', [
            'feeds' => $feeds->toJson(),
            'feed_items' => $feed_items->toJson(),
            'title' => $feed->title,
            'link' => $feed->link,
            'active' => $feed->id
        ]);
    }
}
