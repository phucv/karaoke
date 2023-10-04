function bindSelect2FilterData(objParent, open) {
    try {
        let url = objParent.find("select.select2").attr("data-url-ajax");
        // let url = "https://api.github.com/search/repositories";
        if (url) {
            objParent.find("select.select2").select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    method : 'post',
                    data: function (params) {
                        let q =  params.term; // search term
                        let filter = {
                            q: params.term,
                            limit: params.limit,
                            offset: (parseInt(params.page) - 1) * params.limit,
                            search: {'search_all' : {q}},
                        };
                        if ($(this).data("search")) {
                            filter['filter'] = $(this).data("search");
                        }
                        return filter;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        params.limit = data.limit || 30;
                        return {
                            results: data.record_list_data,
                            pagination: {
                                more: (params.page * params.limit) < data.count_record_list_data
                            }
                        };
                    },
                    cache: true
                },
                // placeholder: 'Search for a repository',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                // tags: true,
                dropdownParent: objParent,
                open: true
            });
        } else {
            objParent.find("select.select2").select2({tags: true, dropdownParent: objParent, open: true});
        }
        if (open) {
            objParent.find("select.select2").select2('open');
        }
    } catch (err) {
        console.log(err);
    }
}

function formatRepo (repo) {
    if (repo.loading) {
        return repo.text;
    }
    return repo.name;
}

function formatRepoSelection (repo) {
    if (repo.full_name) {
        $(repo.element).text(repo.full_name);
    }
    return repo.full_name || repo.text;
}