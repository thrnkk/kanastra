import moment from "moment"

export const dateMixin = {
    methods: {
        fromISO8601(isoString, format = 'DD/MM/YYYY HH:mm:ss') {
            return moment(isoString).format(format)
        },

        toISO8601(dateAsString) {
            return moment(dateAsString, 'DD/MM/YYYY HH:mm:ss').toISOString()
        },
    }
}