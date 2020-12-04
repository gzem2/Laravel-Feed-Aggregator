import React, { Component } from 'react';

export default class SelectFeed extends Component {
    handleSelect = (e) => {
        const selected_feed_id = e.currentTarget.getElementsByClassName('selectfeed_menu_item_details')[0].innerText;
        const selected_feed_title = e.currentTarget.getElementsByClassName('selectfeed_menu_item_details')[1].innerText;

        fetch("/feed/" + selected_feed_id)
            .then(res => res.json())
            .then(
                (result) => {
                    this.props.updateRoot({ items: result, title: selected_feed_title, active: selected_feed_id, page: 1, last_page: false })
                },
                (error) => {
                    this.props.updateRoot({ error })
                }
            );
    }

    render() {
        return (
            <div className="selectfeed_component">
                <label>Select RSS feed:</label><br />
                <div className="selectfeed_container">
                    {this.props.feeds.slice(0).reverse().map((feed, index) => (
                        <div onClick={this.handleSelect} key={feed.title + index.toString()} className={`selectfeed_menu_item ${feed.id == this.props.active ? "active" : ""}`}>
                            <div className="selectfeed_menu_item_details" style={{ display: `none` }}>{feed.id}</div>
                            <div className="selectfeed_menu_item_details selectfeed_menu_item_title">{feed.title}</div>
                            <hr></hr>
                            <div className="selectfeed_menu_item_details selectfeed_menu_item_description">{feed.description}</div>
                            <div className="selectfeed_menu_item_details selectfeed_menu_item_link">{feed.link}</div>
                        </div >
                    ))
                    }
                </div>
            </div >
        );
    }
}