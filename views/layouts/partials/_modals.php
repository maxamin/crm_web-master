<div id="modelRowContextMenuContainer" style="display: none">
    <div id="modelRowContextMenu" class="card">
        <div class="card-body no-padding">
            <ul class="list divider-full-bleed">
                <li class="tile">
                    <a class="tile-content update-model-btn" href="javascript:void(0);">
                        <div class="tile-text">Редактировать</div>
                        <div class="tile-icon"><i class="fa fa-pencil"></i></div>
                    </a>
                </li>
                <li class="tile">
                    <a href="javascript:void(0);" class="tile-content delete-model-btn" data-href="" data-name="">
                        <div class="tile-text">Удалить</div>
                        <div class="tile-icon"><i class="fa fa-trash"></i></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="removeModelConfirm" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="simpleModalLabel">Удаление</h4>
            </div>
            <div class="modal-body">
                <p id="removeModelConfirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <a type="button" id="removeModelLink" class="btn btn-danger" href="javascript:void(0);" data-ajax="0" data-method="post">Удалить</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>