/**
 * Criado por Emerson Santos
 */

import React, {Component} from 'react';
import {Text, View, StyleSheet, ScrollView, AsyncStorage} from 'react-native';
import { TouchableOpacity } from 'react-native-gesture-handler';
import { NavigationActions, StackActions} from 'react-navigation';

const resetAction = StackActions.reset({
  index: 0,
  actions: [
    NavigationActions.navigate({ routeName: 'TelaLogin'})
  ]
})

export default class Relatorios extends Component{

  _signOutAsync = async () => {
    await AsyncStorage.clear();
    this.props.navigation.dispatch(resetAction);
  };
  render() {
    const { navigation } = this.props;
      return (
        <ScrollView style={{height: '100%', backgroundColor: '#81C8AA'}}>
          <View style={styles.container}>
            <Text style={styles.textoApre}>Estamos contando seu consumo</Text>
            <Text style={styles.textoLitros}>10 L</Text>
            <Text style={styles.textoCultivo}>{navigation.getParam('sensor', 'Erro')}</Text>
            <View style={{width: '100%'}}>
              <TouchableOpacity style={styles.btn} onPress={this._signOutAsync}>
                <Text style={{textAlign: 'center', fontSize: 30, color: '#81C8AA'}}>Parar</Text>
              </TouchableOpacity>
            </View>
            
          </View>
        </ScrollView>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#81C8AA',
    padding: 10,
    marginTop: 100
  },
  textoApre:{
    textAlign: 'center',
    fontSize: 30,
    color: '#FFF',
    marginBottom: 50
  },
  textoLitros: {
    fontSize: 45,
    color: '#FFF',
    marginBottom: 30
  },
  textoCultivo:{
    fontSize: 25,
    color: '#FFF'
  },
  btn:{
    backgroundColor: '#FFF',
    width: '100%',
    padding: 20,
    borderRadius: 5,
    marginTop: 30
  }
});