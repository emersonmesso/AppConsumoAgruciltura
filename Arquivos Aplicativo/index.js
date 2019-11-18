/**
 * @autor Emerson Ribeiro Dos Santos
 * @email emersonmessoribeiro@gmail.com
 */

import {AppRegistry} from 'react-native';
import {name as appName} from './app.json';
import {createAppContainer} from 'react-navigation';
import { createStackNavigator } from 'react-navigation-stack';

/* IMPORTAÇAO DAS TELAS*/
import Login from './components/View/Login';
import Home from './components/View/Home';
import Splash from './components/View/Splash';
import Perfil from './components/View/Perfil';
import Relatorios from './components/View/Relatorios'


const MainNavigator = createStackNavigator({
    TelaSplash: {screen: Splash, navigationOptions:{header: null}},
    TelaHome: {screen: Home, navigationOptions:{header: null}},
    TelaLogin: {screen: Login, navigationOptions:{header: null}},
    Relatorios: {screen: Relatorios, navigationOptions:{header: null}},
});

const App = createAppContainer(MainNavigator);

AppRegistry.registerComponent(appName, () => App);
