import React from 'react';
import ReactDOM from 'react-dom';
import bootstrap from 'bootstrap';
import { BrowserRouter, Route, Link, Switch } from "react-router-dom";
import Home from './components/Home'
import FormLogin from './components/FormLogin';
import Menu from './components/Menu';
import Character from './Characters';
import css from '../css/app.css';
 
 class App extends React.Component {
        
     render() {
         return (
                <BrowserRouter>
                        <div>
                                <Menu />
                                <Switch>
                                <Route exact path="/" component={Home}/>
                                <Route path="/login" component={FormLogin}/>
                                </Switch>
                        </div>  
                </BrowserRouter>
         );
     }
 }
const userId = document.getElementById("user_id").value;

window.USER_DETAILS = {
        user_id: userId
};
 
 ReactDOM.render(<BrowserRouter><App user_id={userId} /></BrowserRouter>, document.getElementById('root'));