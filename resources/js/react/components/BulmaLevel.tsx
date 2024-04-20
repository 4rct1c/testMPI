type Props = {
    label: string
    value: string|null
}

const BulmaLevel = (props: Props) => {
    return <div className="level">
        <div className="level-left">
            <div className="level-item">
                {props.label}
            </div>
        </div>
        <div className="level-right">
            <div className="level-item">
                {props.value !== null ? props.value : "—"}
            </div>
        </div>
    </div>
}

export {BulmaLevel}
