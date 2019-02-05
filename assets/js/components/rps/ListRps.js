import React, {Component} from 'react';
import ReactDOM from 'react-dom';

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

    render() {
        // console.log(this.state.personnages);

        const rps = this.state.rps.map(rp => {
            return (
                <div className="d-flex align-items-center mb-1" key={rp.id}>
                    {rp.title}
                    {
                        rp.appCharacter.map((character) => {
                        return (
                            <ul ><li>{character.name}</li></ul>
                        )
                        })
                    }
                    <button id={rp.id} onClick={(e) => this.handleDelete(e.target.id)} className="ml-2" color="danger">Supprimer</button>
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

                <h2>Liste des personnes</h2>
                    {rps}
            </div>
        );
    }
}

ReactDOM.render(<ListRps/>, document.getElementById('rps-list'));