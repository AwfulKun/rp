import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import FormRps from './FormRps';
import {BrowserRouter, Router, HashRouter, Link, Route, Switch} from "react-router-dom";

class ListRps extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            titre: '',
            lien: '',
            statut: '',
            statuts: [],
            personnages: [],
            personnages_add: [],
            rps: [],
            editForm:
                {
                    id: '',
                    titre: '',
                    lien: '',
                    statut: '',
                    personnages: []
                },
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
        fetch('http://127.0.0.1:8000/character/api', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => this.setState({personnages : data}))
        ;
        fetch('http://127.0.0.1:8000/status/', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => this.setState({statuts : data}))
        ;
    }

    // CHANGEMENT DU STATE A LA MODIF DU INPUT
    handleChange(event) {
        this.setState({
            [event.target.name]: event.target.value
        });
    }
    handleChangeMultiple(event) {
        this.setState({personnages_add: Array.from(event.target.selectedOptions, (item) => parseInt(item.value))});
    }


    // ENVOI DU FORMULAIRE
    handleSubmit(event) {
        event.preventDefault();
        fetch('http://127.0.0.1:8000/rp/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                titre: this.state.titre,
                statut: parseInt(this.state.statut),
                lien: this.state.lien,
                personnages: this.state.personnages_add
            })
        })
            .then(response => response.json())
            .then(data => {
                alert('Nouvel utilisateur ajouté !');
                this.addRp(data);
                this.setState({
                    titre: '',
                    statut: '',
                    lien: '',
                    personnages: []
                })
            })
            .catch(err => console.log(err))
        ;
    }

    // FONCTION SUPPRIMER
    handleDelete(id) {
        let confirm = window.confirm("Voulez-vous vraiment supprimer le RP ?");
        if (confirm) {
            fetch('http://127.0.0.1:8000/rp/', {
                method: 'DELETE',
                body: JSON.stringify({
                    id: parseInt(id)
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert('RP supprimé !');
                    this.deleteRp(id);
                })
                .catch(err => console.log(err))
            ;
        } else {

        }
    }

    // ACUTALISER A L'EDITION D'UNE DEPENSE
    updateRp(data) {
        //this.setState({rps: JSON.parse(data)});
        this.update();
    }

    // Fonction de mise à jour des personnes (nouveau fetch)
    update() {
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

    deleteRp(id) {
        this.update();
    }

    // ACTUALISER A L'AJOUT D'UNE PERSONNE
    addRp(data) {
        let rps = this.state.rps;
        rps.push(JSON.parse(data));
        this.setState({rps: rps});
    }

    // FONCTION EDITER
    handleEdit(e, id, titre, lien, statut, personnages) {
        this.setState({editForm:
                {
                    id: id,
                    titre: titre,
                    lien: lien,
                    statut: statut,
                    personnages: personnages
                }
        });
        window.scrollTo(0, 250);
    }

    render() {
        // console.log(this.state.personnages);

        const url = window.location.pathname;
        console.log(url);

        const rps = this.state.rps.map(rp => {
            let persos = [];
            return (
                <div className="d-flex align-items-center mb-1" key={rp.id}>
                    {rp.title}

                    {
                        rp.appCharacter.map((character) => {
                            persos.push(character.id)
                        return (
                            <ul key={character.id}><li>{character.name}</li></ul>
                        )
                        })
                    }
                    <button id={rp.id} onClick={(e) => this.handleDelete(e.target.id)} className="ml-2" color="danger">Supprimer</button>
                    <Link to="/rp/index/edit" onClick={(e) => this.handleEdit(e,rp.id, rp.title, rp.link, rp.status.id, persos)} className="btn btn-primary align-self-center ml-2">Modifier</Link>

                </div>
            )
        });

        const characters = this.state.personnages.map((char) => <option key={char.id} value={char.id}>{char.name} {char.surname}</option>);
        const statut = this.state.statuts.map((s) => <option key={s.id} value={s.id}>{s.label}</option>);


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

                <Link to="/rp/index/add" className="btn btn-primary">Ajouter</Link>
                <Route path="/rp/index/add" render={props=><FormRps {...props} edit="false" statuts={this.state.statuts} personnages={this.state.personnages} url="/rp/index" addRp={data => this.addRp(data)} />} />
                <Route exact path="/rp/index/edit" render={props=><FormRps {...props} edit="true" data={this.state.editForm} personnages={this.state.personnages} statuts={this.state.statuts} url="/rp/index" updateRp={data => this.updateRp(data)} />} />


                <h2>Liste des personnes</h2>
                    {rps}
            </div>
        );
    }
}

ReactDOM.render(<BrowserRouter><ListRps/></BrowserRouter>, document.getElementById('rps-list'));