# Sistema Salas

Sistema em Laravel para o gerenciamento dos eventos de uma unidade de acordo com as salas e horários disponíveis.

# Recursos

- Visualização do calendário de reservas de cada sala (semanal, diário e mensal)
- Busca pelas reservas filtrando por categoria, finalidade, data e título
- Administração dos usuários permitidos para reservar dentro de uma Categoria
- Configurar se a sala precisa ou não de aprovação
- Administração dos usuários responsáveis por cada sala, que gerenciarão os pedidos de reservas para as salas que precisam de aprovação
- Envio de e-mail aos solicitantes e responsáveis
- Administração das finalidades para as reservas
- Administração dos Perídos letivos e suas respectivas janelas de reservas
- Criação e edição de reservas em massa caso haja repetição do evento
- Validação para que reservas não sejam cadastradas caso haja sobreposição de data, horário e sala
- Configuração por sala de bloqueio informando o motivo
- Configuração por sala de antecedência mínima em dias para uma reserva
- Configuração por sala da duração mínima e máxima em minutos de uma reserva
- Configuração por sala dos limites de datas para as reservas:
    - Data limite fixa (data máxima para uma reserva)
    - Data limite dinâmica (máximo de dias a partir da solicitação para uma reserva)
    - Datas limites definidas pelo Período Letivo (janelas de reserva por período letivo)
- Consumo de dados via API, recuperando informações acerca das salas, categorias e reservas do dia
 
# Gerenciando Pessoas Cadastradas na Categoria

As pessoas cadastradas nas categorias são aquelas que poderão realizar reservas na categoria em questão. Para cada categoria é possível gerenciar as pessoas cadastradas de duas formas: pelo número USP ou pelo vínculo com a unidade em questão.

Pelo número USP é necessário inserir manualmente o número USP da pessoa desejada na aba de edição categoria, e ela será cadastrada na categoria possibilitando realizar reservas.

O gerenciamento pelo vínculo é referente somente aos vínculos de Docente, Servidor e Estagiário, e possui três opções:

- **USP**: todos os docentes, servidores e estagiários que entrarem com a senha única no sistema poderão realizar reservas na categoria.
- **Unidade**: todos os docentes, servidores e estagiários que entrarem com a senha única e pertecerem à unidade que o sistema estiver configurado poderão realizar reservas na categoria.
  (A unidade é mostrada com base na variável de ambiente `REPLICADO_CODUNDCLG` do `.env`)
- **Setor**: somente os docentes, servidores e estagiários que pertencem ao(s) setor(es) poderão realizar reservas na categoria.
  (O código da unidade para identificar os logins próprios ou de outras unidades é baseado na variável de ambiente SENHAUNICA_CODIGO_UNIDADE do .env)
- **Nenhum**: somente as pessoas cadastradas manualmente poderão realizar reservas na categoria.

# Gerenciando Restrições na Sala

As salas podem conter algumas restrições de datas para as reservas. A ativação de qualquer uma dessas opções altera o funcionamento do sistema, especialmente nas novas solicitações:

- **Bloqueio**: Impede novas reservas na sala. Essa condição pode ser útil em caso de manutenção ou qualquer outra situação em que a sala não pode ser reservada.
- **Antecedência mínima**: Quantidade de dias de antecedência mínima para reservar a sala.
- **Duração das reservas**: As reservas para a sala podem ser limitadas a uma duração mínima e a uma duração máxima em minutos.
- **Limites de datas para as reservas**: Podem ser por Data limite fixa, Data limite dinâmica ou Data limite por Período Letivo (janela de reserva).

# Finalidades

Por padrão o sistema é instalado com as seguintes finalidades para as reservas:

- Graduação
- Pós-Graduação
- Especialização
- Extensão
- Defesa
- Qualificação
- Reunião
- Evento

Mas todo gerenciamento de finalidades como adicionar, editar ou excluir, pode ser feito na aba de configurações. Permitindo a customização das finalidades para que melhor se adéque à unidade que for utilizar o sistema.

# Realizando Reservas

Ao solicitar reserva, pode-se exibir um texto com instruções de preenchimento ao usuário. Isso é configurado por sala, no cadastro das salas. Também pode-se exigir que o usuário clique numa checkbox com instruções de "aceite" (por exemplo, "Concordo com os termos e uso desta sala, etc. etc."), e isso também é configurado por sala, no cadastro das salas.

# Realizando Reservas com Aprovação

Na aba de edição da sala é possível gerenciar os responsáveis pela sala através do número USP, bem como configurar se a sala necessita de aprovação ou não para a reserva. No caso de precisar de aprovação, quando uma pessoa autorizada tentar realizar uma reserva na sala, a reserva será feita com um *status* de pendente, um e-mail será enviado para os responsáveis da sala e estes devem analisar o pedido de reserva no sistema, aprovando ou recusando.

É possível configurar, por sala, um prazo para a aprovação ou recusa da reserva. Se, passado esse prazo, o(s) responsável(is) pela sala não aprovarem nem rejeitarem a reserva, ela será automaticamente aprovada, e e-mails serão enviados tanto para o solicitante da reserva quanto para o(s) responsável(is) pela sala.

# Consumo de dados via API

Através da API, é possível consultar as informações básicas sobre as salas, as categorias, as finalidades de reserva e as reservas do dia, sendo possível filtrar as reservas do dia por sala ou finalidade. Todas as infomações são retornadas em formato JSON.

Os _endpoints_ disponíveis e seus respectivos retornos estão listados [aqui](docs/endpoints_api.md).

## Utilizando a API com Drupal

Ao utilizar o sistema de gerenciamento de conteúdo [Drupal](https://www.drupal.org/), é possível fazer uso da API utilizando o módulo Views Json Source, que pode ser instalado via composer com o comando `composer require drupal/views_json_source`.

Com este módulo unido ao Drupal, é possível consumir os dados da API e apresentá-los em componentes da página, como views e blocos. Desta forma, os dados apresentados sempre estarão em sincronia com o sistema de reservas, visto que ao modificar as reservas no sistema de reservas, estas modificações também serão apresentadas via API.

# Como subir a aplicação

## Instalação

**Envio de e-mails**:

Para que o envio de e-mails funcione deve-se configurar as variáveis MAIL... no .env.

```sh
composer install
cp .env.example .env
php artisan key:generate
```

**Para ambiente de produção:**

```bash
php artisan migrate
```

**Para ambiente de desenvolvimento:**

```bash
php artisan migrate:fresh --seed
```

**Para acessar a aplicação:**

```sh
php artisan serve
```

# Histórico

Este sistema foi transferido da FFLCH para o USPDev.

- 15/09/2023: Permitindo selecionar qual o nível de vínculo que poderá realizar reserva na categoria.
- 27/09/2023: Permitindo configurar necessidade de aprovação da sala e administração dos responsáveis.
- 10/10/2023: Removendo coluna `cor` da tabela `reservas` e implementando a opção de finalidades.
- 17/10/2023: Arrumando envio de e-mail no momento de solicitar e aprovar/recusar reservas.
- 18/10/2023: Implementando legenda de finalidades no calendário da sala.
- 18/10/2023: Arrumando *seeders*.
- 26/10/2023: Atualizando biblioteca do calendário.
- 26/10/2023: Permitindo realizar reservas ao clicar no calendário.
- 01/11/2023: Adicionado filtro por finalidade.
- 14/11/2023: Não permitindo realizar reservas no passado.
- 16/11/2023: Adicionada interface de restrições por sala.
- 24/11/2023: Cadastro de períodos letivos e cadastro de solicitantes por setor na categora.
- 14/08/2024: Possibilitando consumo de dados via API.
- 12/05/2025: Adicionado prazo na sala para aprovação automática da reserva.


#####################
## Funcionalidades ##
#####################

- usuários não logados podem consultar as reservas de hoje (menu "Hoje"), calendário por sala (menu "Calendário por Sala"), e a relação de salas (menu "Filtro de Recursos");
- usuários logados podem, em adição, solicitar novas reservas (menu "Nova reserva"), bem como consultar relação das reservas que ele solicitou (menu "Minhas Reservas");
- "owners": quem cadastrou reservas pode editá-las e excluí-las;
- responsáveis por salas podem aprovar, recusar, editar e excluir reservas solicitadas nas suas salas;
- admins também podem aprovar, recusar, editar e excluir quaisquer reservas;
- admins podem, em adição, cadastrar e consultar categorias, cadastrar e consultar salas, cadastrar e consultar recursos, cadastrar e consultar períodos letivos, cadastrar e consultar finalidades, cadastrar e consultar usuários e assumir outras identidades.


###################
## Configurações ##
###################

No arquivo de configuração .env:
- SENHAUNICA_ADMINS define os admins do sistema;
- SENHAUNICA_GERENTES não está sendo utilizado;
- RESERVA_CAMPOS_EXTRAS define eventuais campos extras de preenchimento obrigatório ao solicitar reserva;
- MAIL_COPIAR_REMETENTE define se todos os e-mails enviados pelo sistema também serão copiados (em bcc) para o remetente.
