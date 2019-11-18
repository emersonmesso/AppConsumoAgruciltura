/**
 * Criado por Emerson Santos
 */

import React, {Component} from 'react';
import {Text, View, StyleSheet, Image, AsyncStorage, ScrollView, Picker, TouchableHighlight, ProgressBarAndroid, Dimensions} from 'react-native';
import PureChart from 'react-native-pure-chart';
import { NavigationActions, StackActions} from 'react-navigation';
import { TouchableOpacity } from 'react-native-gesture-handler';

const resetAction = StackActions.reset({
  index: 0,
  actions: [
    NavigationActions.navigate({ routeName: 'TelaLogin'})
  ]
})


export default class Home extends Component{
  constructor(props){
    super(props);
    this.state ={
      data: [],
      sensores: [],
      idUser: this.props.navigation.getParam('id'),
      sensor: [],
      nomeSensor: '',
      width: Dimensions.get('window').width,
      height: Dimensions.get('window').height,
      isProcess: true,
      selected: false
    }
    this.abrirContador = this.abrirContador.bind(this);
  }
  async componentDidMount(){
        const userToken = await AsyncStorage.getItem('userToken');

        // Preload data from an external API
        // Preload data using AsyncStorage
        const data = await this.performTimeConsumingTask();
      
        if (data !== null) {
          this.setState({idUser : userToken});
        }
    
    return fetch('https://aplicativo.padraotorrent.com/Backend/pages/api/getDados.php?id=' + this.state.idUser)
      .then((response) => response.json())
      .then((responseJson) => {

        this.setState({
          data : responseJson,
          sensores : responseJson.sensores,
          isProcess: false,
          sensor: responseJson.sensores[0],
          selected: true
        });

      })
      .catch((error) =>{
        console.error(error);
      });
      
  }
  performTimeConsumingTask = async() => {
    return new Promise((resolve) =>
      setTimeout(
        () => { resolve('result') },
        3000
      )
    );
  }

  /*FUNÇÕES*/
  _signOutAsync = async () => {
      await AsyncStorage.clear();
      this.props.navigation.dispatch(resetAction);
  };

  pickerChange(index){
    console.log('Mudando...');
       this.setState({
          selected:true,
          sensor: this.state.sensores[index],
          nomeSensor: this.state.sensores[index].nome,
      })
   }

   abrirContador(nome){
     console.log(nome);
    this.props.navigation.navigate('Relatorios', {sensor: nome});
   }




  render() {
    let sampleData = [
      {
        seriesName: 'series1',
        data: [
          {x: '2018-02-01', y: 30},
          {x: '2018-02-02', y: 200},
          {x: '2018-02-03', y: 170},
          {x: '2018-02-04', y: 250},
          {x: '2018-02-05', y: 10}
        ],
        color: '#297AB1'
      }
    ]
      return (
          <ScrollView style={styles.container}>
              {(this.state.isProcess)?
                    <View style={{width: '100%', flex: 1, height:this.state.height, position: 'absolute', backgroundColor: '#7C0AFF', elevation: 3}}>
                        <ProgressBarAndroid styleAttr="Horizontal" color="#FFF" />
                    </View>
                :<View>
                    <View style={styles.header}>
                        <View style={{flex: 1, alignItems: 'center'}}>
                          <Text style={styles.textHeader}>U. Irrigação: Cultivo de Banana</Text>
                          <Text style={styles.textHeader}>Total Utilizado: 300 L</Text>
                        </View>
                        <View style={{flex: 1, alignItems: 'flex-start', marginTop: 50}}>
                          <Picker
                            value={this.state.sensor}
                            selectedValue={this.state.sensor}
                            style={{height: 50, width: '100%'}}
                            onValueChange={(itemValue, itemIndex) => this.pickerChange(itemIndex)}
                            >
                            {
                              this.state.sensores.map( (v)=>{
                                return <Picker.Item label={v.nome} value={v.id} />
                              })
                            }
                          </Picker>
                        </View>
                    </View>

                    {(this.state.selected)?
                        <View>
                          <View style={styles.card}>
                              <View style={styles.headerCard}>
                                <Text style={styles.cardTitle}>Consumido Hoje</Text>
                              </View>
                              <View style={{width: '100%'}}>
                                  <PureChart data={this.state.sensor.consumo_mes} type='line' height={250} />
                              </View>
                          </View>
                          <View style={styles.card}>
                            <View style={styles.headerCard}>
                              <Text style={styles.cardTitle}>Consumido no Mês</Text>
                            </View>
                            <View style={{width: '100%'}}>
                                <PureChart data={this.state.sensor.consumo_mes} type='line' width={this.state.width} height={250} />
                            </View>
                          </View>
                          <View style={styles.viewBotao}>
                            <TouchableOpacity style={{padding: 15}} onPress={this.abrirContador.bind(this, this.state.sensor.nome)}>
                              <Text style={{textAlign: 'center', fontSize: 22}}>Iniciar Contador</Text>
                            </TouchableOpacity>
                          </View>
                        </View>
                        
                      :<View />}
                </View>
              }
          </ScrollView>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF',
    height: '100%',
  },
  header:{
    flex: 1,
    flexDirection: 'column',
    margin: 10,
    justifyContent: 'center',
    padding: 20,
    borderRadius: 5,
    borderWidth: 1,
    backgroundColor: '#F5FCFF',
    borderWidth: 1,
    borderColor: '#81C8AA',
    borderBottomWidth: 0,
    shadowColor: '#81C8AA',
    shadowOffset: {width: 3, height: 3},
    shadowOpacity: 0.8,
    shadowRadius: 2,
    elevation: 2,
  },
  textHeader:{
    fontSize: 18
  },
  headerCard:{
    width: '100%',
    padding: 10,
    backgroundColor: '#81C8AA'
  },
  cardTitle:{
    textAlign: 'center',
    fontSize: 20,
  },
  image:{
    width: 50,
    height: 50,
  },
  card:{
    flex: 1,
    flexDirection: 'column',
    margin: 10,
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: 5,
    borderWidth: 1,
    backgroundColor: '#F5FCFF',
    borderWidth: 3,
    borderColor: '#81C8AA',
    borderBottomWidth: 3,
    shadowColor: '#81C8AA',
    shadowOffset: {width: 3, height: 3},
    shadowOpacity: 0.8,
    shadowRadius: 2,
    elevation: 2,
    zIndex: 1,
  },
  nomeSensor: {
    fontSize: 30,
    textAlign: 'center',
  },
  textoGrafico:{
    fontSize: 20,
    textAlign: 'center',
    marginBottom: 5,
    width: '100%',
  },
  cardsGrafico:{
    margin: 5,
    marginTop: 20,
    padding: 10,
  },
  viewBotao:{
    flex: 1,
    margin: 5,
    backgroundColor: '#81C8AA',
    width: '96%'
  }
});