import React, {Component} from 'react';
import {Redirect, Link} from "react-router-dom";

class FormRps extends Component {
    constructor(props) {
        super(props);
        if (this.props.data) {
            this.state = {
                titre: props.data.titre,
                lien: props.data.lien,
                statut: props.data.statut,
                statuts: [],
                personnages: props.data.personnages,
                personnages_add: [],
                redirect: false
            }
        } else {
            this.state = {
                titre: '',
                lien: '',
                statut: '',
                statuts: [],
                personnages: [],
                personnages_add: [],
                redirect: false
            }
        }

    }

    // ACTUALISATION DU CONTENU DU FORMULAIRE
    componentWillReceiveProps(props) {
        if (this.props.data) {
            this.setState({
                titre: props.data.titre,
                lien: props.data.lien,
                statut: props.data.statut,
                personnages: props.data.personnages
            });
        }


    }

    componentDidMount() {
        console.log("titre 2:" + this.state.titre);
        console.log("Lien 2:" + this.state.lien);
        console.log("Statut2 :" + this.state.statut);
        console.log("Personnages2 :" + this.state.personnages);

    }

    // CHANGEMENT DU STATE AU CHANGEMENT DE L'INPUT
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

        // Si la route se termine en "add", on envoie la requête en post
        if (this.props.edit != "true") {
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
                    this.props.addRp(data);
                    this.setState({redirect: true})
                })
                .catch(err => console.log(err))
            ;
            // Sinon la route se termine en "edit" et on envoie la requête en post pour éditer
        } else {
            fetch('http://127.0.0.1:8000/rp/' + this.props.data.id + '/edit', {
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
                    alert('Dépense modifiée !');
                    this.props.updateRp(data);
                    this.setState({redirect: true})
                })
                .catch(err => console.log(err))
            ;
        }
    }



    render() {

        // Selon comment la route se termine, le titre affiche 'modifier' ou 'ajouter'
        let titre;

        if (this.props.edit == "true") {
            titre = <h2>Editer une dépense</h2>;
        } else {
            titre = <h2>Ajouter une nouvelle dépense</h2>;
        }

        // REDIRECTION APRES ENVOI DU FORMULAIRE
        if (this.state.redirect == true) {
            return <Redirect to={this.props.url}/>
        }
        console.log(this.props.url);
        const characters = this.props.personnages.map((char) => <option key={char.id} value={char.id}>{char.name} {char.surname}</option>);
        const statut = this.props.statuts.map((s) => <option key={s.id} value={s.id}>{s.label}</option>);

        return (
            <div>
                {titre}
                <form onSubmit={this.handleSubmit.bind(this)}>
                    <div className="d-flex align-items-center">
                        <label htmlFor="titre" className="mr-2 font-weight-bold">Titre</label>
                        <input type="text" value={this.state.titre} onChange={this.handleChange.bind(this)} name="titre" id="titre" placeholder="Titre" required />
                    </div>
                    <div className="d-flex align-items-center">
                        <label htmlFor="lien" className="mr-2 font-weight-bold">Lien</label>
                        <input type="text" value={this.state.lien} onChange={this.handleChange.bind(this)} name="lien" id="lien" placeholder="Lien" />
                    </div>
                    <div className="form-group">
                        <label htmlFor="statut">Statut</label>
                        <select value={this.state.statut} onChange={this.handleChange.bind(this)} className="form-control" name="statut" id="statut">
                            { statut }
                        </select>
                    </div>
                    <div className="form-group">
                        <label htmlFor="personnages_add">Example multiple select</label>
                        <select value={this.state.personnages_add} onChange={this.handleChangeMultiple.bind(this)} multiple={true} name="personnages_add" className="form-control" id="personnages_add">
                            { characters }
                        </select>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        );
    }
}

export default FormRps;