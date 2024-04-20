export const dateForHumans = (timestamp: string|null|undefined, withTime: boolean = false) => {
    if (timestamp === null || timestamp === undefined) return '—'
    let [date, time] = timestamp.split(' ')
    if (date === undefined || time === undefined) return timestamp
    let [year, month, day] = date.split('-')
    return day + '.' + month + '.' + year + (withTime ? ' ' + time : '')
}
