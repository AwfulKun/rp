import React, {Component} from 'react';
import ReactDOM from 'react-dom';

class ListRps extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            titre: '',
            lien: '',
            statut: '',
            personnages: [],
            rps: []
        };

    }

    componentDidMount() {
        fetch('http://127.0.0.1:8000/rp/api', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => this.setState({rps : data}))
        ;
    }

    // CHANGEMENT DU STATE A LA MODIF DU INPUT
    handleChange(event) {
        this.setState({
            [event.target.name]: event.target.value
        });
    }

    // ENVOI DU FORMULAIRE
    handleSubmit(event) {
        event.preventDefault();
        fetch('http://localhost/dcdev/php/expenshare/public/person/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                firstname: this.state.firstname,
                lastname: this.state.lastname,
                sharegroup: this.props.id
            })
        })
            .then(response => response.json())
            .then(data => {
                alert('Nouvel utilisateur ajouté !');
                this.props.addPerson(data);
                this.setState({
                    firstname: '',
                    lastname: ''
                })
            })
            .catch(err => console.log(err))
        ;
    }

    // FONCTION SUPPRIMER
    handleDelete(id) {
        let confirm = window.confirm("Voulez-vous vraiment supprimer l'utilisateur ?");
        if (confirm) {
            fetch('http://localhost/dcdev/php/expenshare/public/person/', {
                method: 'DELETE',
                body: JSON.stringify({
                    id: parseInt(id)
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert('Utilisateur supprimé !');
                    this.props.deletePerson(id);
                })
                .catch(err => console.log(err))
            ;
        } else {

        }
    }

    render() {
        console.log(this.state.rps);

        const rps = this.state.rps.map(rp => {
            return (
                <div className="d-flex align-items-center mb-1" key={rp.title}>
                    {rp.title}
                    {
                        rp.appCharacter.map((character) => {
                        return (
                            <ul ><li>{character.name}</li></ul>
                        )
                        })
                    }
                </div>
            )
        });


        // CHARGEMENT DE LA PAGE
        if (this.state.rps.length === 0) {
            return (
                <div>
                    <div>Chargement en cours...</div>
                </div>
            )
        }

        return (
            <div>
                <h1>Rps</h1>

                <h2>Liste des personnes</h2>
                    {rps}
            </div>
        );
    }
}

ReactDOM.render(<ListRps/>, document.getElementById('rps-list'));