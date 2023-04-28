// take a string and replace all the whitespaces with underscores
function toHashTag(string) {
    string = string.split(" ").map(word => capitalize(word)).join(" ")
    return `#${string.replace(/\s/g, "")}`
}

// capitalize a letter
function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
}

function parseLevel(level) {
    return isNaN(level) ? level : `Grade ${level}`
}

function unshift(array, value) {
    array.unshift(value)
    return array
}

export { toHashTag, capitalize, parseLevel, unshift }
