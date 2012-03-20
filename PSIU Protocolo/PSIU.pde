/******************************************************************
                          PSIU PROTOCOL

 * Copyright (C) Ricardo Mariz and Robolivre.org 2012 <ricardo@robolivre.org>
 * This is a free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * PSIU PROTOOCOL is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
                       
 * data:  02/03/2012
 * Projeto realizado com fundos do Conselho Nacional de Desenvolvimento Científico e Tecnológico (CNPQ)   
                          
                          
Alguns exemplos de comandos que irao funcionar nessa versao:

MNERIM 034 parafrente 100 PC 02123
MNERIM 032 paratras 100 PC 01919
MNERIM 035 giradireita 100 PC 02217
MNERIM 036 giraesquerda 100 PC 02338
MNERIM 035 quantoscomandos PC 02514
MNERIM 034 exibecomando 1 PC 02225
?? 029 qualseunome PC 01755


******************************************************************/


char nome[] = "MNERIM";
char caractere, checkSum[6], tamanho[4], bufferComando[20], bufferParametro[20], remetente[20];
int contByte = 0, soma = 0;
long icheckSum;
  

//VARIAVEIS AUXILIARES
int validouComando = 0, comandoOk = 0 , criaRemetente = 0;
int qntInt, qntFloat, qntChar, numComando;
int perguntaNome = 0;
int posicao = 0;




//PONTEIROS PARA A CRIACAO DOS VETORES COM OS PARAMETROS DOS COMANDOS
int *parametroInt;
float *parametroFloat;
char *parametroChar;




//DEFINE QUANTIDADE DE COMANDOS DO MICROCONTROLADOR
#define qntComandos 7


//ESTRUTURA DE COMANDO
struct
{
  char nome[15];
  int parametro1, parametro2, parametro3;

} comando[qntComandos]; //Vetor com a quantidade de comandos.





void setup()
{
  Serial.begin(9600);
  pinMode(8, OUTPUT);
  pinMode(7, OUTPUT);
  pinMode(6, OUTPUT);
  pinMode(5, OUTPUT);
  
  //LISTA DE COMANDOS PREVIAMENTE DECLARADOS
  
  //Aqui eh declarado os comandos que o microcontrolador aceitara.
  
  strcpy(comando[0].nome , "parafrente");
  comando[0].parametro1 = 1;
  comando[0].parametro2 = 0;
  comando[0].parametro3 = 0;
  
  strcpy(comando[1].nome , "paratras");
  comando[1].parametro1 = 1;
  comando[1].parametro2 = 0;
  comando[1].parametro3 = 0;  
   
  strcpy(comando[2].nome , "giradireita");
  comando[2].parametro1 = 1;
  comando[2].parametro2 = 0;
  comando[2].parametro3 = 0;
   
   
  strcpy(comando[3].nome , "giraesquerda");
  comando[3].parametro1 = 1;
  comando[3].parametro2 = 0;
  comando[3].parametro3 = 0;
  
  strcpy(comando[4].nome , "quantoscomandos");
  comando[4].parametro1 = 0;
  comando[4].parametro2 = 0;
  comando[4].parametro3 = 0;
  
  strcpy(comando[5].nome , "exibecomando");
  comando[5].parametro1 = 1;
  comando[5].parametro2 = 0;
  comando[5].parametro3 = 0;
  
  
  strcpy(comando[6].nome, "qualseunome");
  comando[6].parametro1 = 0;
  comando[6].parametro2 = 0;
  comando[6].parametro3 = 0;
  
  
  
  
}





void loop()
{
  if ( verificarComando() == 1 ) 
  {
    processaComando(bufferComando, parametroInt, parametroFloat, parametroChar); 
    zerarVariaveis();    
  }  
}






int verificarComando()
{

 while (Serial.available() > 0)
 {

   caractere = Serial.read();
 
  //NOME, OS PRIMEIROS BYTES
  
   if(posicao == 0)
   {
      
     if ( ((nome[contByte] == caractere) || (caractere == '?')) && (caractere != 32))
     {
       contByte++; // Se o caractere vindo da Serial for igual ao caractere do nome o contador (contByte) eh  incrementado em 1.
     }
     
     else  if (caractere == 32)
     {
       posicao = 1;
       contByte = 0;
   
     }
     soma = soma + caractere;    
  
   }
   
     
  // BYTES DE TAMANHO
  
   else if (posicao == 1) 
   {
     if(caractere != 32)
     {
      tamanho[contByte] = caractere;
      contByte++;
     }
     else
     {
      posicao = 2;
      contByte = 0;
     }
     soma = soma + caractere;
   }
   
   
   
   
   
   
   //BYTES DE COMANDO 
   
   else if(posicao == 2) //VERIFICA SE O BYTE EH DE COMANDO
   {
   
     if (validouComando == 0) //Verifica se ja validou o comando
     {
      
       if (caractere != 32) //32 = Espaco no ASCII
       {
           bufferComando[contByte] = caractere;
           contByte++;
  
       }
      
       else //Se o caractere for espaco, o nome do comando ja esta no buffer.
       {
         int i;
         
         for(i = 0; i < qntComandos; i++) //Aqui usamos a variavel qntComandos declarada no comeco do programa para checar os comandos existentes.
         {
           if(!strcmp(bufferComando, comando[i].nome))   //Compara a string no buffer com a lista dos comandos definidos, se bater ele aloca dinamicamente variaveis para os parametros.
           {
             //Aloca dinamicamente espaco para colocar os parametros dos comandos
             // e valida o comando.
             parametroInt = (int*)malloc(comando[i].parametro1 * sizeof(int)); 
             qntInt = comando[i].parametro1; //Pega a quantidade parametros inteiro da funcao
             
             parametroFloat = (float*)malloc(comando[i].parametro2 * sizeof(float));
             qntFloat = comando[i].parametro2;//Pega a quantidade de parametros float da funcao.
             
             parametroChar = (char*)malloc(comando[i].parametro3 * sizeof(char));
             qntChar = comando[i].parametro3; //Idem aos de cima.
             
             comandoOk = 1; //O comando eh valido
             numComando = i; //Guarda o numero do comando definido na lista de comandos.
             
    
           }
           
         }
         
         if(comandoOk) //Se o comando for valido continua com a verificacao.
         {
           validouComando = 1; // Comando valido.
           contByte = 0;
           
           if ( ( qntInt == 0) && ( qntFloat == 0) && (qntChar == 0) ) // SE EXISTIR PARAMETROS PARA SEREM LIDOS, O COMANDO NAO TA OK!
           {
             posicao = 3;
           }
           
          
         }
         
         else //Se o comando nao for OK zeraremos as variaveis de soma e contagem de byte para iniciar de novo o processo.
         {
           soma = 0;
           contByte = 0; 
           limpabuffer(bufferComando); 
           
         }
       }    
    
    }
    
    else if (validouComando == 1) //Se o comando ja foi validado, pegaremos os parametros
    {

      if (caractere != 32) //32 = CARACTERE "ESPACO" no ASCII
       {
           bufferParametro[contByte] = caractere;
           contByte++;
       }
       
       else //Se o caractere eh 32 (espaco), ja esta armazenado no bufferParametro o valor do parametro. Armazenaremos nos vetores correspondente.
       {
         if (qntInt > 0) //Checa a quantidade de inteiros que a funcao ainda tem para receber.
         {
           parametroInt[comando[numComando].parametro1 - qntInt] = atoi(bufferParametro);
           qntInt--; //Subtrai quando o parametro for adicionado ao vetor.

         }
         
         else if (qntFloat > 0) //Checa a quantidade de float que a funcao ainda tem para receber.
         {
           parametroFloat[comando[numComando].parametro2 - qntFloat] = atof(bufferParametro);
           qntFloat--;
         }
         
         else if (qntChar > 0) //Checa a quantidade de float que a funcao ainda tem para receber.
         {
           parametroChar[comando[numComando].parametro3 - qntChar] = bufferParametro[comando[numComando].parametro3 - qntChar];
           qntChar--;
         }
         
        
        //Se acabou os parametros, prossegue na mensagem.
        
         if( (qntInt == 0) && (qntFloat == 0) && (qntChar == 0))   //Checa se nao falta mais parametros a ser guardados.       
         { 
            validouComando = 0; //Espera o proximo comando no proximo frame.
            comandoOk = 1;
            posicao = 3;
  
          }

           contByte = 0;
           //limpabuffer(bufferParametro);    //**NAO SEI A UTILIDADE DISSO AINDA.
           
         
       }
      
      
    }
    soma = soma + caractere;
    
    
    
   }
  
  
  //REMETENTE
  
  
  
  
  else if (posicao == 3)
  {
    if(caractere != 32){
      remetente[contByte] = caractere;
      contByte++;
    }
    
    else{
      posicao = 4;
      contByte = 0;
    }
    soma = soma + caractere;
      
  }  
  
  
  
 
  
   //5 BYTES DE CHECKSUM
   
   else if (posicao == 4)
   { 
     checkSum[contByte] = caractere;     
     contByte++;  
 
          // CHECAGEM FINAL COM O CHECKSUM
   
    if (contByte == 5) 
     {
       icheckSum = atol(checkSum);
         
       if (icheckSum == soma)  //SE O CHECKSUM BATER MANDAMOS O NOME DA FUNCAO E SEUS PARAMETROS
      {  
          return 1;
      }
      else
      {
         Serial.println("CHECKSUM NAO BATEU!");
         zerarVariaveis();
         return 0;
      }
      
       
              
         
     }
    
      
   }
      

 }    
      
      
}  
  

void processaComando ( char* comandoRecebido, int* parametroInt, float* parametroFloat, char* parametroChar)
{
  char resposta[20];
  
  if (!(strcmp(comandoRecebido,"parafrente")))
   {
     digitalWrite(8, HIGH);
     digitalWrite(7, HIGH);
     digitalWrite(6, HIGH);
     digitalWrite(5, HIGH);
     delay(parametroInt[0]*10);
     digitalWrite(8, LOW);
     digitalWrite(7, LOW);
     digitalWrite(6, LOW);
     digitalWrite(5, LOW);
     
     strcpy(resposta, "sucesso");
     enviarMensagem(remetente, comandoRecebido, resposta);
          
     
   }
  
   else if (!(strcmp(comandoRecebido,"paratras")))
   {
     digitalWrite(8, LOW);
     digitalWrite(7, HIGH);
     digitalWrite(6, HIGH);
     digitalWrite(5, LOW);
     delay(parametroInt[0]*10);
     digitalWrite(8, LOW);
     digitalWrite(7, LOW);
     digitalWrite(6, LOW);
     digitalWrite(5, LOW);
     
     strcpy(resposta, "sucesso");
     enviarMensagem(remetente, comandoRecebido, resposta);
   }
   
   else if (!(strcmp(comandoRecebido,"giradireita")))
   {
     digitalWrite(8, HIGH);
     digitalWrite(7, HIGH);
     digitalWrite(6, LOW);
     digitalWrite(5, LOW);
     delay(parametroInt[0]*10);
     digitalWrite(8, LOW);
     digitalWrite(7, LOW);
     digitalWrite(6, LOW);
     digitalWrite(5, LOW);
     
     strcpy(resposta, "sucesso");
     enviarMensagem(remetente, comandoRecebido, resposta);
   }

   else if (!(strcmp(comandoRecebido,"giraesquerda")))
   {
     digitalWrite(8, LOW);
     digitalWrite(7, LOW);
     digitalWrite(6, HIGH);
     digitalWrite(5, HIGH);
     delay(parametroInt[0]*10);
     digitalWrite(8, LOW);
     digitalWrite(7, LOW);
     digitalWrite(6, LOW);
     digitalWrite(5, LOW);
     
     strcpy(resposta, "sucesso");
     
     enviarMensagem(remetente, comandoRecebido, resposta);
   } 
  
   else if (!(strcmp(comandoRecebido,"quantoscomandos")))
   {
     char* resposta = "";
     itoa(qntComandos, resposta, 10);
 
     enviarMensagem(remetente, comandoRecebido, resposta);
     
   } 
   
   else if (!(strcmp(comandoRecebido,"exibecomando")))
   {
     char resposta[30];
     char aux[2];
     int qntInt, qntFloat, qntChar;
     

   
     strcpy(resposta, comando[parametroInt[0] - 1].nome);
    
     qntInt = comando[parametroInt[0] -1].parametro1;
     qntFloat = comando[parametroInt[0] -1].parametro2;
     qntChar = comando[parametroInt[0] -1].parametro3;  

     itoa(qntInt, aux, 10);
     strcat(resposta, " ");
     strcat(resposta, aux);

     itoa(qntFloat, aux, 10);
     strcat(resposta, " ");
     strcat(resposta, aux);
             
     itoa(qntChar, aux, 10);
     strcat(resposta, " ");
     strcat(resposta, aux);
     
     enviarMensagem(remetente, comandoRecebido, resposta); 
   } 
   
   else if (!(strcmp(comandoRecebido,"qualseunome")))
   {
     enviarMensagem(remetente, comandoRecebido, NULL);
   } 
}


void enviarMensagem(char* remetente, char* comando, char* resposta)
{
  int soma = 0, i;
  int itamanho = 3 + 5 + 1; // 3 BYTES DO TAMANHO, 5 BYTES DO CHECKSUM, 1 BYTE DE ESPAÇO
  char tamanho[3], nomeAux[20];
  strcpy(nomeAux, nome);
  if (resposta != NULL) strcat(resposta," ");
  strcat(comando," ");
  strcat(remetente," ");
  strcat(nomeAux," ");
  
  itamanho = itamanho + strlen(remetente) + strlen(comando) + strlen(resposta) + strlen(nomeAux);
  itoa(itamanho, tamanho, 10);
  
  
  for (i = 0; i < strlen(remetente); i++)
  {
    soma = soma +  remetente[i];
  }
  
  for (i = 0; i < strlen(comando); i++)
  {
    soma = soma + comando[i];
  }
    
  for (i = 0; i < strlen(resposta); i++)
  {
    soma = soma + resposta[i];
  }
  
  
  for (i = 0; i < strlen(nomeAux); i++)
  {
    soma = soma + nomeAux[i];
  }
  
    for (i = 0; i < strlen(tamanho); i++)
  {
    soma = soma + tamanho[i];
  }
 
  

  
  Serial.print(remetente);
  if ( strlen(tamanho) < 3)
  {
    Serial.print("0");
    soma += 48;
  }
  Serial.print(tamanho);
  Serial.print(" ");
  soma += 32;
  Serial.print(comando);
  if (resposta != NULL) Serial.print(resposta);
  Serial.print(nomeAux);
  if(soma < 10000) Serial.print("0");
  Serial.println(soma);
  
}



//Funcao para limpar os buffers. (Avaliarei depois a eficiencia disso)  
void limpabuffer(char buffer[20])
{
 int i;
  for (i = 0; i < 20; i++)
 {
  buffer[i] = NULL;
 }
}




void  zerarVariaveis()
{
         //Apos executar o comando zera todos os valores e comeca tudo de novo.
       soma = 0;
       contByte = 0; 
       validouComando = 0;
       limpabuffer(bufferComando);
       limpabuffer(bufferParametro);
       limpabuffer(remetente);
       comandoOk = 0;
       criaRemetente = 0;
       perguntaNome = 0;
       posicao = 0;
      
      
}
