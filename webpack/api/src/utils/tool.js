export function tree_label(list, name = 'name') {
  list.forEach(item => {
    item.label = item[name]
    for (var i = 1; i < item.le; i++) {
      if (i === 1) {
        item.label = ' ' + item.label
      }
      item.label = (i === item.le - 1 ? '└' : '─') + item.label
    }
  })
  return list
}

export function list_to_tree(list) {
  return list
}
