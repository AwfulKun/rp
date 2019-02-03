import React, {Component} from 'react';
import { Redirect, Route, Link } from "react-router-dom";

class FormLogin extends Component {
    constructor(props) {
        super(props);
        this.state = {
            email: last_username,
            username: "",
            password: "",
            sharegroup: null,
            user: last_username,
            error: error,
            error_mess,
            token: token
        };
    }

    // CHANGEMENT DU STATE AU CHANGEMENT DE L'INPUT
    handleChange(event) {
        event.preventDefault();
        this.setState({
            [event.target.name]: event.target.value
        });
        console.log("email :" + this.state.email);
        console.log("mdp : " + this.state.password);
    }

    handleSubmit(event) {
        event.preventDefault();
        fetch('http://localhost/www/rp/public/login', {
            method: 'POST',
            body: JSON.stringify({ 
                email: this.state.email,
                password: this.state.password,
                _csrf_token: this.state.token
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data)
            })
            .catch(err => alert('Erreur lors de la création du groupe'))
        ;
    }

    // CREATION D'UN GROUPE
    handleCreate(event) {
        event.preventDefault();
        fetch('http://localhost/dcdev/php/expenshare/public/sharegroup/', {
            method: 'POST',
            body: JSON.stringify({ slug: slugify(this.state.slug) })
        })
            .then(response => response.json())
            .then(data => {
                alert('Nouveau groupe créé avec succès !');
                this.handleOpen(event);
            })
            .catch(err => alert('Erreur lors de la création du groupe'))
        ;
    }

    // ACCES A UN GROUPE
    handleOpen(event) {
        event.preventDefault();
        fetch('http://localhost/dcdev/php/expenshare/public/sharegroup/' + slugify(this.state.slug))
            .then(response => response.json())
            .then(data => {
                console.log(data);
                this.setState({ sharegroup: JSON.parse(data) });
            })
            .catch(err => alert('Ce groupe n\'existe pas !'))
        ;
    }

    render() {

        // Si le groupe existe, on est redirigé
        if (this.state.sharegroup) {
            return <Redirect to={'/'} />
        }
        if (this.state.error != null) {
            const error = this.state.error_mess;
        }
        return (
            
            <form method="post">
                    { error }

                <h1 className="h3 mb-3 font-weight-normal">Please sign in</h1>

                <label htmlFor="inputUsername" className="sr-only">Email</label>
                <input type="text" value={this.state.email} onChange={this.handleChange.bind(this)} name="email" id="inputUsername" className="form-control" placeholder="Username" required autoFocus/>

                <label htmlFor="inputPassword" className="sr-only">Password</label>
                <input type="password" value={this.state.password} onChange={this.handleChange.bind(this)}name="password" id="inputPassword" className="form-control" placeholder="Password" required/>

                <input type="hidden" name="_csrf_token"
                    value="{ this.state.token }"
                />

                <button className="btn btn-lg btn-primary" type="submit">
                    Sign in
                </button>
            </form>
        );
    }
}

export default FormLogin;