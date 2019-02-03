import React, {Component} from 'react';
import {Link} from 'react-router-dom';

class Menu extends Component {
    constructor(props) {
        super(props);
        this.state = {
                user_id: this.props.user_id
        };
    }
    render() {
        if (!this.state.user_id) {
            return (
                <div>
                    <div>
                        <nav>
                            <li className="mr-2"><Link to="/">Accueil</Link></li>
                            <li className="mr-2"><Link to="/login">Login</Link></li>
                        </nav>
                    </div>
                </div>
            );
        } else {
            return (
                <div>
                    <div>
                        <nav>
                            <li className="mr-2"><Link to="/">Accueil</Link></li>
                            <li className="mr-2"><Link to="/logout">Logout</Link></li>
                            { this.state.user_id }
                        </nav>
                    </div>
                </div>
            );
        }
    }
}

export default Menu;