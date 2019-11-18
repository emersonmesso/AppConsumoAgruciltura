/**
 * Criado por Emerson Santos
 */

import React, {Component} from 'react';
import {Text, View, StyleSheet, Image, AsyncStorage} from 'react-native';

export default class Splash extends Component{
    /*Configurações de navegação*/
    static navigationOptions = {
        title: 'Splash',
    };


    /*Construtor*/
    constructor(props) {
        super(props);
      
        this.state = {isLoading: true, userToken: false}
    }

    /*Quando o componente for montado*/
    async componentDidMount() {

        const userToken = await AsyncStorage.getItem('userToken');

        // Preload data from an external API
        // Preload data using AsyncStorage
        const data = await this.performTimeConsumingTask();
      
        if (data !== null) {
          this.setState({ isLoading: false, userToken : userToken});
        }
    } 
    performTimeConsumingTask = async() => {
        return new Promise((resolve) =>
          setTimeout(
            () => { resolve('result') },
            3000
          )
        );
    }

    render() {
        const { navigate } = this.props.navigation;
        if(!this.state.isLoading){

            if(this.state.userToken){
                return navigate('TelaHome');
            }else{
                return navigate('TelaLogin');
            }

            
        }

        return (
            <View style={styles.container}>
                <Image
                style={{width: '50%', height: '30%'}}
                source={require('../Controller/images/logo.png')}/>
                <Text style={{fontSize: 30}}>Meu Aplicativo</Text>
            </View>
        );
    }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5FCFF',
  },
  welcome: {
    fontSize: 20,
    textAlign: 'center',
    margin: 10,
  },
});
