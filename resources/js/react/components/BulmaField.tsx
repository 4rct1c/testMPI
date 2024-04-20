import React from "react";

type Props = {
    label: string
    value?: string|null
    children?: React.ReactNode
}

const BulmaField = (props: Props) => {
    return <div className="field">
        <div className="">
            {props.label}
        </div>
        <div className="field-body">
            {props.value ?? props.children ?? '—'}
        </div>
    </div>
}

export {BulmaField}
