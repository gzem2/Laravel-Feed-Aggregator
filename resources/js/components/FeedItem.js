import React, { Component } from 'react';

export default class FeedItem extends Component {
    constructor(props) {
        super(props)
        this.iframeRef = React.createRef();
    }

    resizeIFrame = () => {
        var fs = parseFloat(getComputedStyle(this.iframeRef.current).fontSize)
        this.iframeRef.current.height = (this.iframeRef.current.contentWindow.document.body.scrollHeight + fs + fs + 3) + 'px';
    }

    render() {
        const pubDate = new Date(this.props.item.pubDate);
        const pubDateString = 'Posted at ' + pubDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + ', ' + pubDate.toLocaleDateString();

        let source;
        if (this.props.item.source && this.props.item.source_url && this.props.item.source != '' && this.props.item.source_url != '') {
            source =
                <div className="feed_item_source">
                    Source: <a href={this.props.item.source_url} target="_blank">{this.props.item.source}</a> <span className="pubDate">{pubDateString}</span>
                </div>
        } else {
            source = <div className="feed_item_source"><span className="pubDate">{pubDateString}</span></div>
        }

        return (
            <div className="feed_item">
                <div className="feed_item_title">
                    <a href={this.props.item.link} target="_blank">{this.props.item.title}</a>
                </div>
                {source}
                <iframe
                    id={"iframe_" + this.props.index}
                    sandbox="allow-same-origin"
                    className="feed_item_description"
                    srcDoc={'<head><meta charset="utf-8"></head>'+this.props.item.description}
                    ref={this.iframeRef}
                    onLoad={this.resizeIFrame}
                />
                <hr></hr>
            </div >
        );
    }
}