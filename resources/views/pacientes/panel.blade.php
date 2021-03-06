@extends('layouts.app')
@section('title') Pacientes @endsection
@section('title_aux') Registro y gestión @endsection
@section('content')
    <style>
        .main-content .page-content .panel table .btn-group {
            z-index: auto !important;
        }
    </style>
    <div class="col-xs-12" id="contenido">
        <div class="panel">
            <div class="panel-header border-bottom">
                <h3>Pacientes registrados</h3>
                <div class="control-btn">
                    <a href="#" class="hidden" data-toggle="modal" data-target="#modal-excel-consulta"><i class="fa fa-file-excel-o"></i> Reporte de consultas</a>
                    <a href="#" class="panel-maximize hidden"><i class="icon-size-fullscreen"></i></a>
                </div>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-sm-12 pull-left">
                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal-registro-paciente">
                            <i class="icon-plus2 white" aria-hidden="true"></i> Crear paciente
                        </button>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-6 col-sm-12 pull-right">
                        <usearch :url="resource_url" :object="pacientes" :value="datos"></usearch>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead class="bg-gray-light">
                    <tr>
                        <th>Identificación</th>
                        <th>Nombre</th>
                        <th>Fecha nacimiento</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(paciente,index) in pacientes" >
                        <td>@{{ paciente.tipo_identificacion.abreviado+' '+paciente.identificacion }}</td>
                        <td>@{{ paciente.nombre_completo }}</td>
                        <td> @{{ paciente.fecha_nacimiento|dateShort }}</td>
                        <td>@{{ paciente.estado }}</td>
                        <td>
                            <div class="btn-group btn-sm">
                                <button type="button" class="btn btn-info" v-on:click="editarPaciente(paciente,index)" data-toggle="modal" data-target="#modal-registro-consulta">Nueva consulta</button>
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Dropdown</span>
                                </button>
                                <span class="dropdown-arrow"></span>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#" v-on:click="editarPaciente(paciente,index)" data-toggle="modal" data-target="#modal-registro-paciente">Actualizar datos</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <v-paginator ref="vpaginator" :resource_url="resource_url" :value="pacientes" @update:value="val => pacientes = val" :datos="datos"></v-paginator>
            </div>
        </div>
        @include('pacientes.partials.form_excel_consulta')
        @include('pacientes.partials.form_registro_consulta')
        @include('pacientes.partials.form_registro_paciente')

    </div>
@endsection

@section('scripts')
    @include('helpers.selectV2')
    @include('helpers.u_search')
    @include('helpers.switch')
    @include('helpers.button_save')
    <script>
        $('#mmenu-fw-pacientes').addClass('active');
        var app = new Vue({
            el : '#contenido',
            data : {
                datos : {
                    busqueda : '',
                },
                pacientes:[],
                resource_url:'/pacientes/obtener-pacientes',
                modal:{
                    title: '',
                },
                paciente:{},
                indexRegistro:'',
                tiposIdentificacion:[],
                regimenes:[],
                epss:[],
                tiposAreaResidencial:[],
                barrios:[],
                gruposEtnico:[],
                subgruposEtnico:[],
                gruposPoblacional:[],
                programasSocial:[],
                generos:[],
                beneficiarios:[],
                serviciosUpgd:[],
                programaDisabled:'',
                subgrupoDisabled:'',
                titleSubgrupoEtnico:'',
                maxDateBorn:'',
                consulta:{},
                variable4:'',
                variable5:'',
                pacienteConsulta:{},
                semanaEPI:'',
                edad:{},
                clasificacion:{
                    imc:'',
                    hg:{
                        tipo_diagnostico_id:'',
                        zs:'',
                        dv:'',
                        cn:'',
                        clase:''
                    },pesotalla:{
                        tipo_diagnostico_id:'',
                        zs:'',
                        dv:'',
                        cn:'',
                        clase:'',
                    },
                    tallaedad:{
                        tipo_diagnostico_id:'',
                        zs:'',
                        dv:'',
                        cn:'',
                        clase:'',
                    },
                    pcedad:{
                        tipo_diagnostico_id:'',
                        zs:'',
                        dv:'',
                        cn:'',
                        clase:'',
                    },
                    pesoedad:{
                        tipo_diagnostico_id:'',
                        zs:'',
                        dv:'',
                        cn:'',
                        clase:'',
                    },
                    imcedad:{
                        tipo_diagnostico_id:'',
                        zs:'',
                        dv:'',
                        cn:'',
                        clase:'',
                    }
                },
                laSemana:{
                    excel_semana:'',
                    maxWeek:'',
                }
            },
            watch: {
                'paciente'(val){
                    this.pacienteConsulta.paciente  = val;
                },
                'clasificacion'(val){
                    this.pacienteConsulta.clasificacion  = val;
                },
                'consulta'(val){
                    this.pacienteConsulta.consulta  = val;
                },
                'paciente.grupo_etnico_id'(val) {
                    if(val){
                        var app = this;
                        var x = app.gruposEtnico.find(function(value, index){
                            return value.id == val;
                        });
                        if(x.subgrupo_etnico.length>0){
                            app.subgruposEtnico = x.subgrupo_etnico;
                            app.titleSubgrupoEtnico = x.label;
                        }else{
                            app.subgruposEtnico = [];
                            app.titleSubgrupoEtnico = '';
                            app.paciente.subgrupo_etnico_id = '';
                        }
                    }
                },
                'paciente.beneficiario'(val) {
                    if(val=='Sí'){
                        this.programaDisabled = false;
                    }else{
                        this.programaDisabled = true;
                        this.paciente.programa_social = [];
                    }
                },
                'paciente.subgrupo_etnico_id'(val) {
                    if(val.length>0){
                        app.subgrupoDisabled = false;
                    }else{
                        app.subgrupoDisabled = true;
                    }
                },
                'consulta.fecha_consulta'(val){
                    app.cambioFechaNacimiento();
                    app.semanaEPI = moment(String(val)).format('w');
                },
                'paciente.fecha_nacimiento'(val) {
                    if(val){
                        app.cambioFechaNacimiento();
                    }
                },
            },
            filters:{
                dateShort:function(val){
                    if (val) {
                        return moment(String(val)).format('DD-MM-YYYY');
                    }
                },
                decimal2:function (val) {
                    if(val){
                        return val.toFixed(2);
                    }
                }
            },
            components: {
                VPaginator: VuePaginator
            },
            methods: {
                exportado(data){
                    if(data.estado=='ok'){
                        window.open('/pacientes/excel-exporta-consulta','_blank')
                    }
                },
                clasificacionNutricional(){
                    var app = this;
                    app.$http.post('/pacientes/clasificacion-nutricional',{edad:app.edad, consulta:app.consulta}).then((response)=>{
                        if(response.body.estado=='ok'){
                            app.clasificacion = response.body.clasificacion;
                        }
                    },(error)=>{
                        toastr.error('Error en servidor:: '+error.status + ' '+error.statusText+' ('+error.url+')');
                    });
                },
                cambioFechaNacimiento(paciente, index){
                    var app = this;
                    app.$http.post('/pacientes/procesaedad-pacientes',{paciente:paciente?paciente:app.paciente, consulta:app.consulta}).then((response)=>{
                        if(response.body.estado=='ok'){
                            app.edad = response.body.edad;
                            app.resetDetalleConsulta();
                            $.each( response.body.paciente.rango_edad.variable, function( key, value ) {
                                app.consulta.detalle_consulta.push({rango_edad_variable_id:value.pivot.id, valor:''});
                                if(value.tipo_input=='select'){
                                    var objectSelect = [];
                                    $.each( value.valor.split(","), function( key, valuex ) {
                                        objectSelect.push({valor:valuex});
                                    });
                                    value.valor = objectSelect;
                                }
                            });

                            if(paciente && index>-1){
                                app.paciente = JSON.parse(JSON.stringify(paciente));
                                var itemsPrograma = [];
                                $.each( app.paciente.programa_social, function( key, value ) {
                                    itemsPrograma.push(value.id);
                                });
                                app.paciente.programa_social = itemsPrograma;
                                app.paciente.estado = this.paciente.estado=='Activo'?1:0;
                                app.indexRegistro = index;
                                app.consulta.fecha_consulta = moment(new Date()).format('YYYY-MM-DD');
                            }
                            app.paciente.rango_edad = response.body.paciente.rango_edad;
                            app.variable4='';
                            app.variable5='';
                        }
                    },(error)=>{
                        toastr.error('Error en servidor:: '+error.status + ' '+error.statusText+' ('+error.url+')');
                    });
                },
                guardarPaciente(response){
                    if(response.tipo == 'update'){
                        this.pacientes.splice(app.indexRegistro,1);
                        this.pacientes.splice(app.indexRegistro,0,response.paciente);
                    }else{
                        this.pacientes.push(response.paciente);
                    }
                    $('#modal-registro-paciente').modal('hide');
                },
                guardarConsulta(response){
                    if(response.tipo == 'update'){
                        this.pacientes.splice(app.indexRegistro,1);
                        this.pacientes.splice(app.indexRegistro,0,response.paciente);
                    }else{
                        this.pacientes.push(response.paciente);
                    }
                    $('#modal-registro-consulta').modal('hide');
                },
                formReset() {
                    this.paciente = {
                        id:'',
                        tipo_identificacion_id:'',
                        identificacion:'',
                        nombre1:'',
                        nombre2:'',
                        apellido1:'',
                        apellido2:'',
                        nombre_completo:'',
                        fecha_nacimiento:'',
                        genero:'',
                        tipo_area_residencial_id:'',
                        barrio_id:'',
                        direccion:'',
                        telefono:'',
                        regimen_id:'',
                        ep_id:'',
                        grupo_etnico_id:'',
                        subgrupo_etnico_id:'',
                        grupo_poblacional_id:'',
                        beneficiario:'',
                        programa_social:[],
                        rango_edad_id:'',
                        estado:'',
                        rango_edad:{
                            variable:[]
                        }
                    };
                    this.consulta={
                        id:'',
                        servicio_upgd_id:'',
                        fecha_consulta:'',
                        detalle_consulta:[]
                    };
                    this.subgruposEtnico=[];
                    this.titleSubgrupoEtnico='';
                    this.variable4='';
                    this.variable5='';
                    this.pacienteConsulta = {
                        paciente:this.paciente,
                        consulta:this.consulta,
                        clasificacion:this.clasificacion
                    };
                    this.semanaEPI = '';
                    this.edad = {};
                },
                resetDetalleConsulta(){
                    this.consulta.detalle_consulta=[];
                    this.clasificacion ={
                        imc:'',
                        hg:{
                            tipo_diagnostico_id:'1',
                            zs:'',
                            dv:'',
                            cn:'',
                            clase:''
                        },pesotalla:{
                            tipo_diagnostico_id:'2',
                            zs:'',
                            dv:'',
                            cn:'',
                            clase:'',
                        },
                        tallaedad:{
                            tipo_diagnostico_id:'3',
                            zs:'',
                            dv:'',
                            cn:'',
                            clase:'',
                        },
                        pcedad:{
                            tipo_diagnostico_id:'4',
                            zs:'',
                            dv:'',
                            cn:'',
                            clase:'',
                        },
                        pesoedad:{
                            tipo_diagnostico_id:'5',
                            zs:'',
                            dv:'',
                            cn:'',
                            clase:'',
                        },
                        imcedad:{
                            tipo_diagnostico_id:'6',
                            zs:'',
                            dv:'',
                            cn:'',
                            clase:'',
                        }
                    };
                },
                resetSemana(){
                    this.laSemana={
                        excel_semana:'',
                            maxWeek:'',
                    }
                },
                cambiox(index){
                    if(index==4){
                        this.variable4=index;
                    }else if(index==5){
                        this.variable5=index;
                    }
                    this.clasificacionNutricional();
                },
                editarPaciente(paciente, index) {
                    this.cambioFechaNacimiento(paciente, index);
                },
                complementosPaciente() {
                    this.$http.get('/pacientes/complementos-pacientes').then(
                        (response)=>{
                            this.tiposIdentificacion = response.body.tiposIdentificacion;
                            this.regimenes = response.body.regimenes;
                            this.epss = response.body.epss;
                            this.tiposAreaResidencial = response.body.tiposAreaResidencial;
                            this.barrios = response.body.barrios;
                            this.gruposEtnico = response.body.gruposEtnico;
                            this.gruposPoblacional = response.body.gruposPoblacional;
                            this.programasSocial = response.body.programasSocial;
                            this.generos = response.body.generos;
                            this.beneficiarios = response.body.beneficiarios;
                            this.serviciosUpgd = response.body.serviciosUpgd;
                        },(error)=>{
                            toastr.error(error.status + ' '+error.statusText+' ('+error.url+')');
                        }
                    );
                },
            },
            beforeMount(){
                this.formReset();
            },
            mounted(){
                var app = this;
                app.complementosPaciente();
                app.maxDateBorn = moment(new Date()).format('YYYY-MM-DD');
                app.laSemana.maxWeek = moment(new Date()).format('YYYY-[W]ww');
                $('#modal-registro-paciente').on("hidden.bs.modal", function () {
                    app.formReset();
                });
                $('#modal-registro-paciente').on("show.bs.modal", function () {
                    app.modal.title = (app.paciente.id != ''?'Edición de ':'Nuevo ') + 'paciente';
                    app.errors.clear('excel_consulta');
                });

                $('#modal-registro-consulta').on("hidden.bs.modal", function () {
                    app.formReset();
                });

                $('#modal-registro-consulta').on("show.bs.modal", function () {
                    $('.nav-tabs-consulta').find('li:nth-child(1)').find('a').click();
                    app.modal.title = 'Registro de nueva consulta';
                    app.errors.clear('excel_consulta');
                });

                $('#modal-excel-consulta').on("hidden.bs.modal", function () {
                    app.resetSemana();
                });
                $('#modal-excel-consulta').on("show.bs.modal", function () {
                    app.modal.title = 'Reporte de consultas';
                    app.errors.clear('consulta');
                    app.errors.clear('paciente');
                });
            },
        });
    </script>
@endsection
