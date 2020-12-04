import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class AddFeed extends Component {
    constructor(props) {
        super(props)
        this.inputRef = React.createRef();
    }

    handleAdd = () => {
        const new_feed_url = this.inputRef.current.value;
        if (new_feed_url) {
            fetch("/feed", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'feed_url': new_feed_url
                })
            }).then(res => res.json())
                .then(
                    (result) => {
                        this.props.updateRoot({
                            'items': result['items'],
                            'feeds': result['feeds'],
                            'title': result['title'][0],
                            'active': result['active'],
                        })
                        this.inputRef.current.value = '';
                    },
                    (error) => {
                        this.props.updateRoot(error)
                    },
                );
        }
    }
    
    render() {
        return (
            <div className="addfeed_component">
                <label>Add RSS feed:</label><br />
                <input ref={this.inputRef} type="url" className="newfeed" name="newfeed"></input>
                <button onClick={this.handleAdd}>Add</button>
            </div>
        );
    }
}
