<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $xml = simplexml_load_file($request->input('feed_url'));

        $feed = new Feed();
        $feed->feed_url = $request->input('feed_url');
        $feed->title = $xml->channel->title;
        $feed->link =  $xml->channel->link;
        $feed->description = $xml->channel->description;
        $feed->save();
        foreach ($xml->channel->item as $item) {
            $model = new FeedItem;
            $model->feed_id = $feed->id;
            $model->title = $item->title;
            $model->link = $item->link;
            $model->description = $item->description;
            $model->pubDate = date('m-d-Y h:i:s A T', strtotime($item->pubDate));
            $model->source = $item->source;
            $model->source_url = $item->source->attributes()->url;

            $model->save();
        }
        $feed_items = FeedItem::where('feed_id', $feed->id)->orderBy('pubDate', 'desc')->offset(0)->limit(10)->get();
        $feeds = Feed::all();
        return response()->json([
            'items' => $feed_items,
            'feeds' => $feeds,
            'title' => $feed->title,
            'active' => $feed->id
        ]);
    }

    /**
     * Fetch new feed items
     */
    public function refresh(Feed $feed)
    {
        $xml = simplexml_load_file($feed->feed_url);
        $new_entry_count = 0;
        $latest_entry_date = FeedItem::latest()->first()->pubDate;
        foreach ($xml->channel->item as $item) {
            $itemdate = date('m-d-Y h:i:s A T', strtotime($item->pubDate));
            //if (!FeedItem::where('pubDate', $itemdate)->exists()) {
            if ($itemdate > $latest_entry_date) {
                $model = new FeedItem;
                $model->feed_id = $feed->id;
                $model->title = $item->title;
                $model->link = $item->link;
                $model->description = $item->description;
                $model->pubDate = $itemdate;
                $model->source = $item->source;
                $model->source_url = $item->source->attributes()->url;
                $model->save();
                $new_entry_count += 1;
            }
        }
        $feed_items = FeedItem::where('feed_id', $feed->id)->orderBy('pubDate', 'desc')->offset(0)->limit(10)->get();
        return response()->json([
            'items' => $feed_items,
            'newentry' => $new_entry_count,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        $feed_items = FeedItem::where('feed_id', $feed->id)->orderBy('pubDate', 'desc')->offset(0)->limit(10)->get();
        return $feed_items;
    }

    /**
     * Display with offset
     */
    public function page(Feed $feed)
    {
        //$feed_items = FeedItem::where('feed_id', $feed->id)->orderBy('pubDate', 'desc')->offset($request->offset)->limit(10)->get();
        $feed_items = FeedItem::where('feed_id', $feed->id)->paginate(10);
        return $feed_items;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function edit(Feed $feed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feed $feed)
    {
        $model = Feed::where('id', $feed->id)->first();
        $model->title = $request->input('title');
        $model->link = $request->input('link');
        $model->description = $request->input('description');
        $model->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feed $feed)
    {
        $model = Feed::where('id', $feed->id)->first();
        $model->delete();

        $feeds = Feed::all();
        if (!$feeds->isEmpty()) {
            $lastfeed = $feeds->last();
            $feed_items = FeedItem::where('feed_id', $lastfeed->id)->orderBy('pubDate', 'desc')->offset(0)->limit(10)->get();
            return response()->json([
                'items' => $feed_items,
                'feeds' => $feeds,
                'title' => $lastfeed->title,
                'active' => $lastfeed->id
            ]);
        } else {
            return response()->json([
                'items' => [[
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
                ]],
                'feeds' => [],
                'title' => [0 => "No RSS feed"],
                'active' => 0
            ]);
        }
    }
}
