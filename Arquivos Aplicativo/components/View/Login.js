/**
 * Criado por Emerson Santos
 */

import React, {Component} from 'react';
import {Text, View, StyleSheet, TextInput, TouchableOpacity, AsyncStorage, ProgressBarAndroid} from 'react-native';
import { NavigationActions, StackActions} from 'react-navigation';


const resetAction = StackActions.reset({
    index: 0,
    actions: [
      NavigationActions.navigate({ routeName: 'TelaHome'})
    ]
  })

export default class Login extends Component{
    constructor(props) {
        super(props);
        this.state = {
            campoEmail: '',
            campoSenha: '',
            entrou: false,
            isErro: false,
            msgErro: '',
            isProcess: false,
            idUser: 0
        }
        this._verifica = this._verifica.bind(this);
        this._entrar = this._entrar.bind(this);
    }
    

    /*FUNÇÕES */

    _verifica(){
        //https://aplicativo.padraotorrent.com/Backend/pages/api/loginUser.php
        this.setState({isProcess: true});

        if(this.state.campoEmail == '' || this.state.campoSenha == ''){
            //não entra
            this.setState({msgErro : 'Dados não informados!', isErro: true});
            this.setState({isProcess: false});
        }else{
            fetch('https://aplicativo.padraotorrent.com/Backend/pages/api/loginUser.php?email=' + this.state.campoEmail+'&senha=' + this.state.campoSenha)
            .then((response) => response.json())
            .then((responseJson) => {
                console.log(responseJson);
                //resposta
                if(responseJson.result.login){
                    this.setState({entrou: true, idUser : responseJson.result.id});
                    this._entrar();
                }else{
                    this.setState({msgErro : responseJson.result.msg, isErro: true});
                    this.setState({isProcess: false});
                }
                

            })
            .catch((error) => {
                this.setState({msgErro : 'Verifique sua Internet!', isErro: true});
                this.setState({isProcess: false});
            });
        }
        
        
    }
    _entrar = async () => {
        try{
            await AsyncStorage.setItem('userToken', this.state.idUser);
            this.props.navigation.dispatch(resetAction);

        } catch(_err){
            this.setState({msgErro : 'Verifique suas credenciais!', isErro: true});
        }
    };

    render() {

        

        return (
            <View style={styles.container}>
                <Text style={styles.welcome}>Bem Vindo de Volta</Text>
                
                <TextInput
                    style={styles.campoEmail}
                    keyboardType='email-address'
                    placeholder='Sem E-mail'
                    onChangeText={(text) => this.setState({campoEmail: text})}
                />                
                <TextInput
                    style={styles.campoEmail}
                    secureTextEntry={true}
                    placeholder='Sua Senha'
                    onChangeText={(text) => this.setState({campoSenha: text})}
                />
                <TouchableOpacity style={styles.botao}
                    onPress={this._verifica.bind()}
                >
                    <Text style={styles.textoBotao}>Entrar</Text>
                </TouchableOpacity>

                {(this.state.isErro)?
                <View style={styles.viewErro}>
                    <Text style={{fontSize: 20, color: '#FFF'}}>{this.state.msgErro}</Text>
                </View>
                :
                <View />}

                {(this.state.isProcess)?
                    <View style={{width: '100%', height: '100%', position: 'absolute', backgroundColor: '#81C8AA'}}>
                        <ProgressBarAndroid styleAttr="Horizontal" color="#FFF" />
                    </View>
                :<View />}

                

            </View>
        );
    }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 10,
    backgroundColor: '#81C8AA',
  },
  welcome: {
    fontSize: 40,
    marginBottom: 40,
    textAlign: 'center',
    color: '#FFF',
  },
  campoEmail:{
      backgroundColor: '#FFF',
      width: '100%',
      height: 70,
      alignItems: 'center',
      justifyContent: 'center',
      marginTop:10,
      marginBottom: 10,
      marginLeft: 100,
      marginRight: 100,
      fontSize: 20,
      padding: 20,
      borderColor: '#FFF',
      borderRadius: 5,
  },
  botao:{
      width: '100%',
      borderColor: '#FFF',
      borderWidth: 1,
      marginLeft: 100,
      marginTop: 10,
      marginRight: 100,
      height: 70,
      alignItems: 'center',
      backgroundColor: '#FFF',
      borderRadius: 10,
  },
  textoBotao:{
    fontSize: 40,
    marginBottom: 40,
    textAlign: 'center',
    color: '#81C8AA',
  },
  viewErro:{
      borderRadius: 10,
      alignItems: 'center',
      marginTop: 20,
      justifyContent: 'center',
      backgroundColor: '#FF7D6E',
      width: '100%',
      height: 50,
  }
});
