window.onload = function () {
    if (typeof formData !== 'undefined') {
        let sMultiOrigin = document.getElementById('flowRoute_origin');
        let sMultiSelected = document.getElementById('flowRoute_selected');
        for (let i in collectionOfAllRoutesData) {
            let rConfig = collectionOfAllRoutesData[i];
            let opt = document.createElement('option');
            opt.value = rConfig['action'];
            opt.innerHTML = rConfig['action'];
            opt.title = rConfig['description'];

            let isGoingTo = false;
            for (let it in goingToRouteData) {
                if (goingToRouteData[it]['action'] === rConfig['action']) {
                    isGoingTo = true;
                }
            }

            if (isGoingTo === true) {
                opt.selected = true;
                sMultiSelected.appendChild(opt);
            }
            else {
                sMultiOrigin.appendChild(opt);
            }
        }

        // preenche o formulário se for uma edição
        if (formData !== null) {
            document.getElementById('flowRoute_method').value = formData['method'];
            document.getElementById('flowRoute_route').value = formData['route'].replace(appName + '/', '/');
            document.getElementById('flowRoute_description').value = formData['description'];
            document.getElementById('flowRoute_devDescription').value = formData['devDescription'];
        }
        else {
            let urlParams = new URLSearchParams(window.location.search);
            let newmethod = urlParams.get('newmethod');
            let newroute = urlParams.get('newroute');

            document.getElementById('flowRoute_method').value = newmethod;
            document.getElementById('flowRoute_route').value = newroute.replace(appName + '/', '/');
        }



        document.getElementById('form').onsubmit = onSubmitValidate;
        document.getElementById('flowRoute_origin').ondblclick = actionMoveIntoMultipleSelect;
        document.getElementById('flowRoute_selected').ondblclick = actionMoveIntoMultipleSelect;
        document.getElementById('flowRoute_newRoute').onkeypress = preventOnEnter;
        document.getElementById('flowRoute_newRoute').onkeyup = addNewRoute;
        document.getElementById('btnExcluir').onclick = onDeleteRoute;
    }
};




var actionMoveIntoMultipleSelect = function (e) {
    if (e.target.title === 'new') {
        e.target.parentNode.removeChild(e.target);
    }
    else {
        let origin = e.target.parentNode;
        let destiny = document.getElementById(origin.attributes['data-multiple-target'].value);
        destiny.appendChild(e.target);
    }
};
var addNewRoute = function (e) {
    if (e.keyCode === 13) {
        let newRoute = e.target.value;
        if (newRoute !== '') {
            let split = newRoute.split(' ');
            let msg = '';

            if (split.length !== 2) {
                msg = 'A rota especificada é inválida.\n';
                msg += 'Use o formato METHOD /route.';
            }
            else {
                let allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
                if (allowedMethods.indexOf(split[0]) === -1) {
                    msg = 'O método especificado é inválido.\n';
                    msg += 'Esperado um dos tipos: GET, POST, PUT, PATCH, DELETE.';
                }
                else {
                    if (checkIfRouteExists(newRoute) === true) {
                        msg = 'A rota especificada já existe.';
                    }
                }
            }

            if (msg !== '') {
                alert(msg);
            }
            else {
                let opt = document.createElement('option');
                opt.value = newRoute;
                opt.innerHTML = newRoute;
                opt.title = 'new';
                opt.selected = true;

                document.getElementById('flowRoute_selected').appendChild(opt);
                e.target.value = '';
            }
        }
    }
};
var preventOnEnter = function (e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        return false;
    }
};
var onDeleteRoute = function(e) {
    var msg = 'Esta ação não pode ser desfeita.\n\n';
    msg += 'Você tem certeza que deseja excluir esta rota?\n';
    msg += 'Com isto, todos os pontos do fluxo que apontam para este item precisarão ser alterados manualmente.';
    if (confirm(msg) === false) {
        e.preventDefault();
        return false;
    }
    else {
        document.getElementById('_method').value = 'DELETE';
        return true;
    }
};



var validateRoute = function (editRoute) {
    let method = document.getElementById('flowRoute_method').value;
    let route = document.getElementById('flowRoute_route').value;
    if (route[0] === '/') { route = route.substr(1); }


    if (method === '') {
        validateMessage.push('Selecione o método HTTP.');
    }
    else if (route === '') {
        validateMessage.push('Defina a rota para esta ação.');
    }
    else {
        let action = method + ' ' + appName + '/' + route;
        if (editRoute !== action) {
            if (checkIfRouteExists(action) === true) {
                validateMessage.push('O nome da rota indicada já existe.');
            }
        }
    }
};
var checkIfRouteExists = function (action) {
    let r = false;
    for (let i in collectionOfAllRoutesData) {
        let rConfig = collectionOfAllRoutesData[i];
        if (rConfig['action'] === action) {
            r = true;
        }
    }
    return r;
};
var validateDescription = function () {
    let desc = document.getElementById('flowRoute_description').value;
    if (desc === '') {
        validateMessage.push('Preencha o campo "Descrição".');
    }
};
var validateDevDescription = function () {
    let desc = document.getElementById('flowRoute_devDescription').value;
    if (desc === '') {
        validateMessage.push('Preencha o campo "Informações Técnicas".');
    }
};


var validateMessage = [];
var onSubmitValidate = function (e) {
    validateMessage = [];
    if (formData === null) { validateRoute(null); }
    else { validateRoute(formData['action']); }
    validateDescription();
    validateDevDescription();


    if (validateMessage.length > 0) {
        e.preventDefault();
        alert(validateMessage.join('\n'));
    }
    else {
        let allOptionsSelected = document.getElementById('flowRoute_selected').childNodes;
        for (let i in allOptionsSelected) {
            allOptionsSelected[i].selected = true;
        }
        return true;
    }

    return false;
};
