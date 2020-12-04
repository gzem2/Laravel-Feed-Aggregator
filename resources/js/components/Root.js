import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SelectFeed from './SelectFeed';
import AddFeed from './AddFeed';
import Feed from './Feed';

export default class Root extends Component {
    constructor(props) {
        super(props)
        this.state = {
            items: JSON.parse(this.props.items),
            feeds: JSON.parse(this.props.feeds),
            active: this.props.active,
            title: this.props.title,
            page: 1,
            last_page: false
        }
    }

    updateRoot = (newState, error = null) => {
        if (!error) {
            this.setState(newState)
        } else {
            this.setState({
                error
            })
        }
    }

    render() {
        return (
            <div className="container">
                <div className="row">
                    <div id="header">
                        <h1 id="header_title">Laravel Feed Aggregator</h1>
                    </div>
                </div>
                <div className="row">
                    <div className="col-3">
                        <AddFeed updateRoot={this.updateRoot} />
                        <SelectFeed id="selectfeed" feeds={this.state.feeds} active={this.state.active} updateRoot={this.updateRoot} />
                    </div>
                    <div className="col-7">
                        <Feed id="feed"
                            title={this.state.title}
                            items={this.state.items}
                            link={this.props.link}
                            active={this.state.active}
                            page={this.state.page}
                            last_page={this.state.last_page}
                            updateRoot={this.updateRoot}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementById('root_component')) {
    const component = document.getElementById('root_component');
    const props = Object.assign({}, component.dataset);
    ReactDOM.render(<Root {...props} />, component);
}