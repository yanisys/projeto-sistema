<div class="modal" tabindex="-1" role="dialog" id="modal-exibe-consulta">
    <div class="modal-dialog" role="document" id="dialog-exibe-consulta">
        <div class="modal-content" id="content-exibe-consulta">
            <div class="modal-body">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <div class="col-md-1">
                    @foreach($ultimos_atendimentos as $u)
                        
                    @endforeach
                </div>
                <div class="col-md-11">
                    <iframe src="{{ route('relatorios/prontuario').'/'.$ultimos_atendimentos[0]->cd_prontuario }}" style="color: {{$ultimos_atendimentos[0]->cor}}" title="Clique para ver o prontuário" width="1200px" height="1000px" scrolling="no" >{{formata_data($ultimos_atendimentos[0]->created_at)}}</iframe>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

