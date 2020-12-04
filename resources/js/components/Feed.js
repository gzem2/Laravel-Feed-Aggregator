import React, { Component } from 'react';
import FeedItem from './FeedItem';

export default class Feed extends Component {
    constructor(props) {
        super(props)
        this.updateRef = React.createRef();
    }

    handleRemove = () => {
        fetch("/feed/" + this.props.active, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                '_token': $('meta[name="csrf-token"]').attr('content'),
            })
        }).then(res => res.json())
            .then(
                (result) => {
                    this.props.updateRoot({
                        'items': result['items'],
                        'feeds': result['feeds'],
                        'title': typeof result['title'] == 'string' ? result['title'] : result['title'][0],
                        'active': result['active'],
                    })
                },
                (error) => {
                    this.props.updateRoot(error)
                }
            );
    }

    flashNewEntry = (newentry) => {
        if (newentry > 0) {
            this.updateRef.current.innerText = newentry + ' new entries';
        } else {
            this.updateRef.current.innerText = 'No new entries';
        }
        setTimeout(() => {
            this.updateRef.current.innerText = 'Update feed';
        }, 3000);
    }

    handleRefresh = () => {
        fetch("/feed/" + this.props.active + "/refresh")
            .then(res => res.json())
            .then(
                (result) => {
                    this.props.updateRoot({ items: result['items'], page: 1, last_page: false })
                    this.flashNewEntry(result['newentry']);
                },
                (error) => {
                    this.props.updateRoot({ error })
                }
            );
    }

    handleLoadMore = () => {
        if (!this.props.last_page) {
            fetch("/feed/" + this.props.active + "/page?page=" + (this.props.page + 1))
                .then(res => res.json())
                .then(
                    (result) => {
                        if (result['data'].length == 0) {
                            this.props.updateRoot({ last_page: true });
                        } else {
                            this.props.updateRoot({
                                'items': this.props.items.concat(result['data']),
                                'page': this.props.page + 1
                            })
                        }
                    },
                    (error) => {
                        this.props.updateRoot(error)
                    },
                );
        }
    }

    render() {
        return (
            <div className="feed_container">
                <div className="row">
                    <div className="card">
                        <div className="card-header">
                            <a href={this.props.link} target="_blank">{this.props.title}</a>
                            <span onClick={this.handleRemove} id="feed_control_remove" className="feed_control">Remove from list</span>
                            <span onClick={this.handleRefresh} id="feed_control_update" className="feed_control" ref={this.updateRef} >Update feed</span>
                        </div>

                        <div className="card-body">
                            {this.props.items.map((item, index) => (
                                <FeedItem key={item.title + index.toString()} item={item} index={index} />
                            ))}
                        </div>
                        {this.props.title.startsWith("No RSS feed") ? "" :
                            <div className="card-footer" align="center">
                                <button onClick={this.handleLoadMore} className="more_button">{this.props.last_page ? ("No more entries") : ("Load More")}</button>
                            </div>}
                    </div>
                </div>
            </div>
        );
    }
}