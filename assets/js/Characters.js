import React from 'react';
import ReactDOM from 'react-dom';

 
 class Characters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            character: {},
        };
    }

    componentDidMount() {
        fetch('http://localhost/rp/public/character/', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => this.setState({character : JSON.parse(data)}))
        ;
    }
        
     render() {
    const TodoListElement = document.querySelector('#todo-list');
    const getData = (name, json = true) => {
        const value = TodoListElement.getAttribute(`data-${name}`);
        return json ? JSON.parse(value) : value;
    };
    
    const element = React.createElement(TodoList, {
        items: getData(items),
    });
         return (
                <h1>{elements}</h1>
         );
     }
 }

 ReactDOM.render(<Characters/>, document.getElementById('todo-list'));