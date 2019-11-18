/**
 * Criado por Emerson Santos
 */

import React, {Component} from 'react';
import {Text, View, StyleSheet, TouchableOpacity, Image, AsyncStorage, ScrollView, Dimensions} from 'react-native';

export default class Home extends Component{

  /*FUNÇÕES*/
  _signOutAsync = async () => {
      await AsyncStorage.clear();
      this.props.navigation.dispatch(resetAction);
  };
  render() {
      return (
          <ScrollView style={styles.container}>
              <View style={styles.header}>
                <View style={{width: '90%'}}><Text style={{fontSize: 30}}>DASBOARD</Text></View>
                <View style={{backgroundColor: '#FFF'}}>
                  <Image
                  style={styles.image}
                  source={require('../Controller/images/user.png')}/>
                </View>

              </View>

              <View style={styles.card}>
                
              </View>
          </ScrollView>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F5FCFF',
  },
  header:{
    flex: 1,
    flexDirection: 'row',
    margin: 10,
    justifyContent: 'center',
    padding: 20,
    borderRadius: 5,
    borderWidth: 1,
    backgroundColor: '#F5FCFF',
    borderWidth: 1,
    borderColor: '#ddd',
    borderBottomWidth: 0,
    shadowColor: '#000',
    shadowOffset: {width: 3, height: 3},
    shadowOpacity: 0.8,
    shadowRadius: 2,
    elevation: 2,
  },
  image:{
    width: 50,
    height: 50,
  },
  card:{
    flex: 1,
    flexDirection: 'row',
    margin: 10,
    padding: 20,
    borderRadius: 5,
    borderWidth: 1,
    backgroundColor: '#F5FCFF',
    borderWidth: 1,
    borderColor: '#ddd',
    borderBottomWidth: 0,
    shadowColor: '#000',
    shadowOffset: {width: 3, height: 3},
    shadowOpacity: 0.8,
    shadowRadius: 2,
    elevation: 2,
  }
});
